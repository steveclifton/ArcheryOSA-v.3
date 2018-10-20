<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\Event;
use App\Models\EventAdmin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventAdminController extends EventController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEventAdminView(Request $request)
    {
        // Get Event
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Unable to access this section');
        }

        // check user can edit event
        if (!Auth::user()->isSuperAdmin()) {
            $eventadmin = EventAdmin::where('eventid', $event->eventid)
                                    ->where('userid', Auth::id())
                                    ->where('canedit', 1)
                                    ->get()->first();

            if (empty($eventadmin)) {
                return back()->with('failure', 'Unable to access this page');
            }
        }

        $eventadmins = EventAdmin::where('eventid', $event->eventid)->get();
        foreach ($eventadmins as $admin) {
            $admin->user = User::where('userid', $admin->userid)->get()->first();
        }

        return view('events.auth.management.admins', compact('event', 'eventadmins'));
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
        $event = Event::where('eventurl', $request->eventurl)->get()->first();
        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        // if not a super user
        if (!Auth::user()->isSuperAdmin()) {
            // check this user is allowed to make changes
            $eventadmin = EventAdmin::where('eventid', $event->eventid)
                ->where('userid', Auth::id())
                ->where('canedit', 1)
                ->get()->first();

            if (empty($eventadmin)) {
                return response()->json([
                    'success' => false,
                    'data'    => ''
                ]);
            }
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
        $user = User::where('email', 'like', '%' .$request->email. '%')->get()->first();
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($user) || empty($event)) {
            $message = 'Can not be processed, please refresh and try again';
            if (empty($user)) {
                $message = 'User email can not be found';
            }
            return response()->json([
                'success' => false,
                'data'    => $message
            ]);
        }


        // if not a super user
        if (!Auth::user()->isSuperAdmin()) {
            // check this user is allowed to make changes
            $eventadmin = EventAdmin::where('eventid', $event->eventid)
                ->where('userid', Auth::id())
                ->where('canedit', 1)
                ->get()->first();


            if (empty($eventadmin)) {
                return response()->json([
                    'success' => false,
                    'data'    => 'Can not be processed'
                ]);
            }

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
        if (empty($request->userid) || empty($user)) {

            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        // Get Event
        $event = Event::where('eventurl', $request->eventurl)->get()->first();
        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data'    => ''
            ]);
        }

        // if not a super user
        if (!Auth::user()->isSuperAdmin()) {
            // check this user is allowed to make changes
            $eventadmin = EventAdmin::where('eventid', $event->eventid)
                ->where('userid', Auth::id())
                ->where('canedit', 1)
                ->get()->first();

            if (empty($eventadmin)) {
                return response()->json([
                    'success' => false,
                    'data'    => ''
                ]);
            }
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
