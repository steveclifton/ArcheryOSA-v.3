<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Events\PublicEvents\ResultsController;
use App\Http\Requests\User\CreateChild;
use App\Http\Requests\User\UpdateChild;
use App\Http\Requests\User\UserUpdateProfile;
use App\Http\Controllers\Controller;
use App\Jobs\SendArcherRelationRequest;
use App\Models\Club;
use App\Models\Division;
use App\Models\Membership;
use App\Models\Organisation;
use App\Models\UserRelation;
use App\User;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class ProfileController extends Controller
{


    /******************************************************************************
     * GET Requests
     ******************************************************************************/

    /**
     * Gets the users dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard()
    {
        return view('profile.auth.profile');
    }

    public function getPublicProfile(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        $scores = 0;

        if (empty($user)) {
            abort(404);
        }

        $data = $this->getcacheditem('userprofile' . $request->username);

        if (empty($data)) {
            $events = DB::select("
            SELECT `e`.`eventid`, `e`.`eventtypeid`, `e`.`label`,
                   CONCAT_WS(' - ', DATE_FORMAT(`e`.`start`, '%d-%M %Y'), DATE_FORMAT(`e`.`end`, '%d-%M %Y')) as date
            FROM `evententrys` ee
            JOIN `events` e USING (`eventid`)
            JOIN `scores_flat` sf USING (`entryid`)
            WHERE ee.`userid` = :userid
            GROUP BY `e`.`eventid`
            ORDER BY `e`.end DESC
           ", ['userid' => $user->userid]);

            $resultscontroller = new ResultsController();

            $finalresults = [];

            $eventIds = array_column($events, 'eventid');

            $flatscores = [];

            if (!empty($eventIds)) {
                $flatscores = DB::select("
                SELECT sf.*, CONCAT_WS(' ', r.label, ec.label) as roundname, r.unit, ec.date as compdate, ec.sequence
                FROM `scores_flat` sf
                JOIN `rounds` r USING (`roundid`)
                JOIN `eventcompetitions` ec ON (sf.`eventcompetitionid` = ec.`eventcompetitionid`)
                WHERE sf.`eventid` IN (" . implode(',', $eventIds) . ")
                AND `sf`.`userid` = :userid
                AND `sf`.`total` <> 0
                ", ['userid' => $user->userid]);
            }

            // Pre-group scores by eventid to reduce queries
            $scoresByEvent = [];
            foreach ($flatscores as $flatscore) {
                $scoresByEvent[$flatscore->eventid][] = $flatscore;
            }

            foreach ($events as $event) {
                $flatscores = $scoresByEvent[$event->eventid] ?? [];

                $scores += count($flatscores);

                if ($event->eventtypeid === 1 || $event->eventtypeid === 3) {
                    $evententry = $resultscontroller->getEventEntrySorted($event->eventid, $user->userid);

                    if (!empty($evententry)) {
                        $results = $this->formatOverallResults($evententry, $flatscores);
                        $result = reset($results);

                        if (empty($result)) {
                            continue;
                        }

                        foreach ($result as $key => $value) {
                            $data = reset($value['results']);
                            unset($data['Archer']);
                            $finalresults['events'][$event->label . '|' . $event->date][$key] = $data;
                        }
                    }
                }
            }

            $scorecount = $scores;
            $eventcount = count($events);

            $data = compact('user', 'eventcount','scorecount', 'finalresults');

            Cache::put('userprofile' . $request->username, $data, 3600);
        }

        if (empty($data)) {
            abort(503);
        }

        return view('profile.public.public-profile', $data);
    }

    /**
     * Gets the users details form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMyDetails()
    {
        $clubs = Club::get();
        $divisions = Division::where('organisationid', 1)->orderby('code')->get();

        return view('profile.auth.mydetails', compact('clubs', 'divisions'));
    }

    public function getMyEvents()
    {
        $myevents = DB::select("
            SELECT e.label, e.start, e.eventurl, es.label as status, evs.label as eventstatus
            FROM `events` e
            JOIN `evententrys` ee USING (`eventid`)
            JOIN `eventstatus` evs USING (`eventstatusid`)
            JOIN `entrystatus` es ON (ee.entrystatusid = es.entrystatusid)
            WHERE `ee`.`userid` = :userid
            ORDER BY `e`.`start` DESC
        ", ['userid' => Auth::id()]);


        return view('profile.auth.events.myevents', compact('myevents'));
    }

    public function getMyResults()
    {
        return view('profile.auth.results.all');
    }

    /******************************************************************************
     * Membership
     ******************************************************************************/
    public function getMemberships()
    {
        $memberships = Auth::user()->getMemberships();

        foreach (Auth::user()->getChildren() as $child) {
            $tmpMembership = $child->getMemberships();

            foreach ($tmpMembership as $membership) {
                $memberships[] = $membership;
            }
        }

        foreach ($memberships as $membership) {
            $membership->username = User::where('userid', $membership->userid)->pluck('firstname')->first();
        }


        return view('profile.auth.membership-list', compact('memberships'));
    }

    public function getMembershipCreate()
    {
        $organisations = Organisation::all();
        return view('profile.auth.membershipcreate', compact('organisations'));
    }

    public function getMembershipUpdate(Request $request)
    {
        $membership = Membership::where('membershipid', $request->membershipid)->first();

        $allowed = ($membership->userid) == Auth::id();

        // Make sure the request is for a user or their child accounts
        if (!$allowed) {

            $children = Auth::user()->getChildren();
            foreach ($children as $child) {
                if ($membership->userid == $child->userid) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                return abort('503');
            }
        }



        $organisations = Organisation::all();
        return view('profile.auth.membershipupdate', compact('organisations', 'membership'));
    }

    public function createMembership(Request $request)
    {

        if (empty($request->membership)) {
            return back()->with('failure', 'Membership number required');
        }

        // check to see if they have one for the organisation - shouldnt have more than 1
        $membership = Membership::where('userid', $request->userid)
                                ->where('organisationid', $request->organisationid)
                                ->first();

        $status = 'Updated!';
        if (empty($membership)) {
            $status = 'Created!';
            $membership = new Membership();
        }

        $membership->userid = $request->userid;
        $membership->membership = $request->membership;
        $membership->organisationid = $request->organisationid;

        $membership->save();

        return redirect('/profile/memberships')->with('success', 'Membership ' . $status);
    }

    public function updateMembership(Request $request)
    {

        if (empty($request->membership)) {
            return back()->with('failure', 'Membership number required');
        }

        // check to see if they have one for the organisation - shouldnt have more than 1
        $membership = Membership::where('membershipid', $request->membershipid)
                                ->first();


        $allowed = !empty($membership) && ($membership->userid === Auth::id());

        // Make sure the request is for a user or their child accounts
        if (!$allowed) {

            foreach (Auth::user()->getChildren() as $child) {
                if ($membership->userid == $child->userid) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                return abort('503');
            }
        }

        if (empty($membership)) {
            return back()->with('failure', 'Cannot Find Membership');
        }

        $membership->membership = $request->membership;
        $membership->organisationid = $request->organisationid;

        $membership->save();

        return redirect('/profile/memberships')->with('success', 'Membership Updated!');
    }

    /******************************************************************************
     * CHILDREN
     ******************************************************************************/
    public function getChildrenList()
    {
        $children = User::where('parentuserid', Auth::id())->get();

        return view('profile.auth.children-list', compact('children'));
    }

    public function getChildCreate()
    {
        return view('profile.auth.childcreate');
    }



    public function getChild(Request $request)
    {
        $child = User::where('username', $request->username)
                        ->where('parentuserid', Auth::id())
                        ->get()
                        ->first();

        if (empty($child)) {
            return back()->with('failure', 'Invalid Request');
        }
        $divisions = Division::where('organisationid', 1)->orderby('code')->get();
        return view('profile.auth.childupdate', compact('child', 'divisions'));
    }


    public function createChild(CreateChild $request)
    {
        $validated = $request->validated();

        $existing = User::where('email', $validated['email'] ?? -1)->get()->first();

        if (!empty($existing)) {
            return back()->with('failure', 'Email Address already has an account, please try send a relationship request');
        }

        $userid = $this->createBasicUser($validated, Auth::id());

        if (empty($userid)) {
            return back()->with('failure', 'Please try again later');
        }

        $userrelation = new UserRelation();
        $userrelation->userid = Auth::id();
        $userrelation->relationid = $userid;
        $userrelation->authorised = 1;
        $userrelation->hash = $this->createHash();

        $userrelation->save();


        return redirect('profile/children')->with('success', 'Child Created');

    }

    public function updateChild(UpdateChild $request)
    {
        $validated = $request->validated();

        $existing = User::where('email', $validated['email'] ?? -1)
                        ->where('username', '<>', $validated['username'])
                        ->get()->first();

        if (!empty($existing)) {
            return back()->with('failure', 'Email Address already has an account, please try send a relationship request');
        }

        $user = User::where('username', $validated['username'])
                    ->where('parentuserid', Auth::id())
                    ->get()
                    ->first();

        if (empty($user)) {
            return back()->with('failure', 'Please try again later');
        }

        $user->firstname = strtolower($validated['firstname']);
        $user->lastname  = strtolower($validated['lastname']);
        $user->email     = !empty($validated['email']) ? $validated['email'] : $user->email;
        $user->anzdivisionid     = !empty($validated['anzdivisionid']) ? $validated['anzdivisionid'] : NULL;
        $user->membership  = strtolower($validated['membership']);
        $user->dateofbirth  = $validated['dateofbirth'];

        $user->save();


        return redirect('profile/children')->with('success', 'Child Updated');

    }


    /******************************************************************************
     * RELATIONSHIPS
     ******************************************************************************/
    public function getRelationshipsList()
    {
        $relations = UserRelation::where('userid', Auth::id())->pluck('relationid')->toarray();

        if (!empty($relations)) {
            $relations = User::wherein('userid', $relations)->get();
            foreach ($relations as $relation) {
                $relation->status = UserRelation::where('userid', Auth::id())
                                                ->where('relationid', $relation->userid)
                                                ->pluck('authorised')->first();
            }
        }


        return view('profile.auth.relationships-list', compact('relations'));
    }

    public function getRelationshipsRequest()
    {
        return view('profile.auth.relation');
    }

    public function requestRelationship(Request $request)
    {
        $user = User::where('email', $request->input('email', null) ?? '')->get()->first();

        if (empty($user)) {
            return back()->with('failure', 'Cannot find a user with that email address');
        }

        // check to see if a relationship already exists
        $userrelation = UserRelation::where('userid', Auth::id())->where('relationid', $user->userid)->get()->first();

        if (!empty($userrelation)) {
            return back()->with('failure', 'Relationship already exists');
        }

        $authfullname = ucwords(Auth::user()->firstname ?? '') . ' ' . ucwords(Auth::user()->lastname ?? '') . '(' . Auth::user()->email . ')';

        $userrelation = new UserRelation();
        $userrelation->userid = Auth::id();
        $userrelation->relationid = $user->userid;
        $userrelation->hash = $this->createHash();
        $userrelation->save();

        SendArcherRelationRequest::dispatch($user->email,
                                            $user->firstname,
                                            $authfullname,
                                            $userrelation->hash,
                                            getenv('APP_URL') . '/profile/relationship/authorise'
        );

        return back()->with('success', 'Request sent!');
    }

    public function authoriseRelation(Request $request)
    {
        $userrelation = UserRelation::where('hash', $request->hash ?? -1)->where('authorised', 0)->get()->first();

        $status = false;

        if (empty($userrelation)) {
            $message = 'Authorisation link invalid (may have already been approved)';
        }
        else {
            $userrelation->authorised = 1;
            $userrelation->save();

            $message = 'Authorisation complate!';
            $status = true;
        }

        return view('profile.public.relationconfirm', compact('message', 'status'));

    }
    public function removeRelationship(Request $request)
    {
        $userid = $request->userid;
        if (empty($userid)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $userrelation = UserRelation::where('userid', Auth::id())->where('relationid', $userid)->get()->first();
        if (empty($userrelation)) {
            // could the be relation trying to remove their relation with another user, try that
            $userrelation = UserRelation::where('userid', $userid)->where('relationid', Auth::id())->get()->first();
        }

        if (empty($userrelation)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        // Delete the user relationship
        //$userrelation->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Relationship Removed'
        ]);

    }








    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    /**
     * Updates a Users Profile
     * @param UserUpdateProfile $request
     * @return redirect
     */
    public function updateProfile(UserUpdateProfile $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $olduser = clone $user;

        $user->firstname   = $validated['firstname'];
        $user->lastname    = $validated['lastname'];
        $user->phone       = $validated['phone'];
        $user->address     = $validated['address'];
        $user->city        = $validated['city'];
        $user->postcode    = $validated['postcode'];
        $user->dateofbirth = $validated['dateofbirth'];
        $user->membership  = $validated['membership'];
        $user->anzdivisionid  = $validated['anzdivisionid'];
        $user->clubid      = $validated['club'];
        $user->gender      = in_array($validated['gender'], ['m', 'f']) ? $validated['gender'] : null ;

        if ($user->firstname != $olduser->firstname || $user->lastname != $olduser->lastname) {
            $user->username = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $validated['firstname'].$validated['lastname'])) . rand(1,1440);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile Updated');
    }



    /**
     * Old Method, needs to be updated
     *  - Only used now in the profilecontroller
     */
    protected function formatOverallResults($entries, $flatscores)
    {
        $numberofec = count(array_column($flatscores, 'eventcompetitionid', 'eventcompetitionid'));

        $eventcompseq = $flatscoressorted = [];
        foreach ($flatscores as $flatscore) {
            // Add scores to a UserID KEY'd array
            $flatscoressorted[$flatscore->userid][] = $flatscore;

            // reformat the round name
            $flatscore->roundname = $flatscore->roundname . date(' - d M', strtotime($flatscore->compdate)) . '|' .$flatscore->eventcompetitionid;

            $eventcompseq[$flatscore->roundname] = $flatscore->sequence;
        }

        // loop over the scores and find the one that matches the div and round
        foreach ($entries as $key => $entry) {

            if (!empty($flatscoressorted[$entry->userid])) {

                // create the array for the entry's scores
                $entry->score = [];

                // they have scores, find the score that matches the details
                foreach ($flatscoressorted[$entry->userid] as $flatscore) {

                    // if its an event
                    if ((!empty($flatscore->eventtypeid) && $flatscore->eventtypeid === 1) && ($entry->divisionid == $flatscore->divisionid)) {
                        $entry->score[$flatscore->roundname] = $flatscore->total;
                        continue;
                    }

                    // league stuff needs to be checked
                    $divMatch = $entry->divisionid == $flatscore->divisionid;
                    $roundMatch = $entry->roundid == $flatscore->roundid;

                    if ($divMatch && $roundMatch) {
                        $entry->score[$flatscore->roundname] = $flatscore->total;
                    }
                }
            }
            else {
                // remove the entry
                unset($entries[$key]);
            }
        }

        $finalResults = [];
        foreach ($entries as $key => $entry) {

            // Make sure they have a score
            if (empty($entry->score)) {
                unset($entries[$key]);
                continue;
            }

            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';

            $key = $gender . $entry->divisionname . ' - ' . ($entry->roundname);

            $finalResults[$entry->bowtype][$key][] = $entry;
        }

        // Sort by sequence
        foreach ($finalResults as $bowtype => &$divisions) {
            foreach ($divisions as $divisionname => &$rounds) {

                // Build an array of all the round names
                $ecomp = [];
                foreach ($rounds as $round) {
                    foreach (array_keys($round->score) as $key) {
                        $ecomp[$key] = $key;
                    }
                }

                // Sort them by the sequence
                uksort($ecomp, function($a, $b) use ($eventcompseq) {
                    if (!isset($eventcompseq[$a]) || !isset($eventcompseq[$b])) {
                        return -1;
                    }
                    if ($eventcompseq[$a] > $eventcompseq[$b]) {
                        return 1;
                    }
                    if ($eventcompseq[$a] < $eventcompseq[$b]) {
                        return -1;
                    }
                    return 0;
                });


                // Add users results into the results array
                foreach ($rounds as $archer) {

                    $result = [];
                    $result['Archer'] = '<a href="/profile/public/'.$archer->username.'">' . ucwords($archer->firstname . ' ' . $archer->lastname) . '</a>';

                    if (!empty($archer->schoolname)) {
                        $result['School'] = ucwords($archer->schoolname);
                    }
                    foreach($ecomp as $key) {
                        $result[$key] = '';
                    }

                    if (count($ecomp) < $numberofec) {
                        foreach (range(count($ecomp), $numberofec - 1) as $i) {
                            $result[$i] = '';
                        }
                    }

                    $totalscore = 0;
                    foreach ($archer->score as $roundname => $score) {
                        $result[$roundname] = $score;
                        $totalscore += $score;
                    }
                    $result['Total'] = $totalscore;
                    $divisions[$divisionname]['results'][] = $result;
                }

                // Sort the results by Total
                usort($divisions[$divisionname]['results'], function($a, $b) {
                    if ($a['Total'] == $b['Total']) {
                        return 0;
                    }
                    if ($a['Total'] < $b['Total']) {
                        return 1;
                    }
                    if ($a['Total'] > $b['Total']) {
                        return -1;
                    }
                    return 0;
                });

            }
        }

        return $finalResults;
    }


}
