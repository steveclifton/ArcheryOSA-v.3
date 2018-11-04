<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\Club;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\School;
use App\User;
use Illuminate\Http\Request;


class EventAdminController extends EventController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEventAdminView(Request $request)
    {
        // Get Event
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return back()->with('failure', 'Unable to access this section');
        }

        $eventadmins = EventAdmin::where('eventid', $event->eventid)->get();
        foreach ($eventadmins as $admin) {
            $admin->user = User::where('userid', $admin->userid)->get()->first();
        }



        return view('events.auth.management.admins', compact('event', 'eventadmins'));
    }

    public function getEventAdminClubView(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return back()->with('failure', 'Unable to access this section');
        }

        $eventadmin = EventAdmin::where('eventadminid', $request->eventadminid)->get()->first();

        if (empty($eventadmin)) {
            return back()->with('failure', 'Event Admin not found');
        }

        $clubids = json_decode($eventadmin->clubid);

        if (empty($clubids)) {
            $clubids = [];
        }

        $clubs = Club::where('visible', 1)->orderby('label')->get();

        return view('events.auth.management.admins.clubs',compact('event', 'eventadmin', 'clubids', 'clubs'));

    }

    public function getEventAdminSchoolView(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return back()->with('failure', 'Unable to access this section');
        }

        $eventadmin = EventAdmin::where('eventadminid', $request->eventadminid)->get()->first();

        if (empty($eventadmin)) {
            return back()->with('failure', 'Event Admin not found');
        }

        $schoolids = json_decode($eventadmin->schoolid);

        if (empty($schoolids)) {
            $schoolids = [];
        }

        $schools = School::where('visible', 1)->orderby('label')->get();

        return view('events.auth.management.admins.schools',compact('event', 'eventadmin', 'schoolids', 'schools'));

    }


    /**
     * POST
     */

    public function addClubsToUser(Request $request)
    {
        // Get Event
        $event = $this->userOk($request->eventurl);
        $eventadmin = EventAdmin::where('eventid', $event->eventid ?? -1)
                                ->where('eventadminid', $request->input('eventadminid'))
                                ->get()
                                ->first();


        if (empty($event) || empty($eventadmin)) {
            return response()->json([
                'success' => false,
                'data'    => 'Please check users email address and try again'
            ]);
        }

        $clubids = explode(',', $request->input('clubids'));
        $eaclubids = [];
        foreach ($clubids as $clubid) {
            $eaclubids[] = intval($clubid);
        }

        $eventadmin->clubid = json_encode($eaclubids);
        $eventadmin->save();

        return redirect('events/manage/eventadmins/' . $event->eventurl)->with('success', 'Clubs Added');

    }

    public function addSchoolsToUser(Request $request)
    {
        // Get Event
        $event = $this->userOk($request->eventurl);
        $eventadmin = EventAdmin::where('eventid', $event->eventid ?? -1)
                                ->where('eventadminid', $request->input('eventadminid'))
                                ->get()
                                ->first();


        if (empty($event) || empty($eventadmin)) {
            return response()->json([
                'success' => false,
                'data'    => 'Please check users email address and try again'
            ]);
        }

        $schoolids = explode(',', $request->input('schoolids'));
        $eaclubids = [];
        foreach ($schoolids as $schoolid) {
            $eaclubids[] = intval($schoolid);
        }

        $eventadmin->schoolid = json_encode($eaclubids);
        $eventadmin->save();

        return redirect('events/manage/eventadmins/' . $event->eventurl)->with('success', 'Schools Added');

    }

    /**
     * AJAX
     */

    public function updateUser(Request $request)
    {

        if (empty($request->type) || empty($request->userid)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        // Get Event
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        // get the event admin that matches the event and userid
        $eventadmin = EventAdmin::where('eventid', $event->eventid)
            ->where('userid', $request->userid)
            ->get()->first();

        if (empty($eventadmin)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        switch ($request->type) {
            case 'canscore' :
                $eventadmin->canscore = !empty($eventadmin->canscore) ? 0 : 1;
                break;

            case 'canedit' :
                $eventadmin->canedit = !empty($eventadmin->canedit) ? 0 : 1;
                break;
        }

        $eventadmin->save();

        return response()->json([
            'success' => true,
            'data'    => ''
        ]);
    }

    public function addUser(Request $request)
    {
        // Get Event
        $event = $this->userOk($request->eventurl);
        $user = User::where('email', 'like', '%' .$request->email. '%')->get()->first();

        if (empty($event) || empty($user)) {
            return response()->json([
                'success' => false,
                'data'    => 'Please check users email address and try again'
            ]);
        }

        // if the new user doesnt have a admin level access, upgrade them for now
        if ($user->roleid == 4) {
            $user->roleid = 3;
            $user->save();
        }

        // make sure they arent already an admin
        $eventadmin = EventAdmin::where('userid', $user->userid)
                                ->where('eventid', $event->eventid)
                                ->get()->first();

        if (!empty($eventadmin)) {
            return response()->json([
                'success' => false,
                'data'    => 'User is already an admin.'
            ]);
        }

        $eventadmin = new EventAdmin();
        $eventadmin->eventid = $event->eventid;
        $eventadmin->userid = $user->userid;
        $eventadmin->canscore = 1;
        $eventadmin->canedit = 1;

        $eventadmin->save();


        return response()->json([
            'success' => true,
            'data'    => ''
        ]);
    }

    public function deleteUser(Request $request)
    {

        $user = User::where('userid', $request->userid)->get()->first();

        // Get Event
        $event = $this->userOk($request->eventurl);

        if (empty($event) || empty($user)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        $eventadmin = EventAdmin::where('eventid', $event->eventid)
            ->where('userid', $request->userid)
            ->get()->first();

        if (empty($eventadmin)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        $eventadmin->delete();

        $eventadmin = EventAdmin::where('userid', $request->userid)->get()->first();

        if (empty($eventadmin)) {
            $user->roleid = 4;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'data'    => ''
        ]);
    }

}
