<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEventUpdate;
use App\Model\Audit;
use App\Models\EventEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventCommunicationController extends EventController
{

    public function getEventCommView(Request $request)
    {

        // return redirect()->back()->with('failure', 'Event emails unavailable at this time.');

        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
        }
        else {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);
        }

        $event = !empty($event) ? reset($event) : null;

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        return view('events.auth.management.emails', compact('event'));
    }


    /**
     * POST
     */

    public function sendEventEmail(Request $request)
    {
        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
        }
        else {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);
        }

        $event = !empty($event) ? reset($event) : null;

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        $type    = $request->input('email');
        $message = $request->input('message');

        if (empty($type) || empty($message)) {
            return back()->with('failure', 'Please check details and try again');
        }

        $filesArr = [];
        $file1 = null;
        if (!empty($request->file('upload1'))) {
            $file = $request->file('upload1');
            $name = $file->getClientOriginalName();
            $destinationPath = public_path('/content/files');

            $file->move($destinationPath, $name);
            $file1 = '/content/files/' . $name;
            $filesArr[] = $file1;
        }

        $file2 = null;
        if (!empty($request->file('upload2'))) {
            $file = $request->file('upload2');
            $name = $file->getClientOriginalName();
            $destinationPath = public_path('/content/files');

            $file->move($destinationPath, $name);
            $file2 = '/content/files/' . $name;
            $filesArr[] = $file2;
        }

        $file3 = null;
        if (!empty($request->file('upload3'))) {
            $file = $request->file('upload3');
            $name = $file->getClientOriginalName();
            $destinationPath = public_path('/content/files');

            $file->move($destinationPath, $name);
            $file3 = '/content/files/' . $name;
            $filesArr[] = $file3;
        }

        $i = 1;
        $count = 0;

        // limit to 2 per minute
        switch($type) {

            case 'all' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->get();


                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'), $event->contactname, $event->email, $filesArr)
                                        ->delay(now()->addMinutes($i));

                    if (++$count == 2) {
                        $count = 0;
                        $i++;
                    }

                }
                break;

            case 'approved' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->where('entrystatusid', 2)->get();

                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'), $event->contactname, $event->email, $filesArr)
                                        ->delay(now()->addMinutes($i));
                    if (++$count == 2) {
                        $count = 0;
                        $i++;
                    }

                }
                break;

            case 'topay' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->where('paid', 0)->get();

                $i = 1;
                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'), $event->contactname, $event->email, $filesArr)
                                        ->delay(now()->addMinutes($i));

                    if (++$count == 2) {
                        $count = 0;
                        $i++;
                    }
                }
                break;

            default:
                return back()->with('failure', 'Please check details and try again');
                break;
        }


        Audit::create([
            'eventid' => $event->eventid,
            'userid' => Auth::id(),
            'class' => __CLASS__,
            'method' => __FUNCTION__,
            'line' => __LINE__,
            'before' => json_encode(['type' => $type, 'message' => $message]),
        ]);


        return redirect('events/manage/' . $event->eventurl)->with('success', 'Email Sent');

    }




}
