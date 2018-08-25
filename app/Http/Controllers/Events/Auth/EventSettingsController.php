<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

class EventSettingsController extends EventController
{

    public function getEventSettingsView(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Invalid');
        }

        $eventstatuses = EventStatus::get();

        return view('events.auth.management.settings', compact('event', 'eventstatuses'));
    }

    public function updateEventSettings(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Invalid');
        }

        $eventadmin = EventAdmin::where('userid', Auth::id())
            ->where('eventid', $event->eventid)
            ->where('canedit', 1)
            ->get()->first();

        if (empty($eventadmin)) {
            return back()->with('failure', 'Cannot edit event');
        }

        if (!empty($request->input('visible'))) {
            $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get()->first();

            if (empty($eventcompetitions)) {
                return back()
                    ->with('failure', 'Event must have competitions before it can be active')
                    ->with('visible', true);
            }
        }

        if (!empty($request->file('imagedt'))) {

            //clean up old image
            if (!empty($event->imagedt)) {
                if (is_file(public_path('images/events/' . $event->imagedt))) {
                    unlink(public_path('images/events/' . $event->imagedt));
                }
            }

            if (!empty($event->imagebanner)) {
                if (is_file(public_path('images/events/' . $event->imagebanner))) {
                    unlink(public_path('images/events/' . $event->imagebanner));
                }
            }
            $image = $request->file('imagedt');

            // Create for cards
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/events/' . $filename);
            Image::make($image)->resize(1024, 641)->save($location);
            $event->imagedt = $filename;

            // Create for banner
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/events/' . $filename);
            Image::make($image)->resize(1400, 587)->save($location);
            $event->imagebanner = $filename;
        }



        $event->adminnotifications = empty($request->input('adminnotifications')) ? 0 : 1;
        $event->entrylimit         = empty($request->input('entrylimit'))         ? NULL : intval($request->input('entrylimit'));
        $event->eventstatusid      = intval($request->input('eventstatusid'));
        $event->visible            = !empty($request->input('visible'))           ? 1 : 0;
        $event->dateofbirth        = !empty($request->input('dateofbirth'))       ? 1 : 0;
        $event->save();

        return back()->with('success', 'Event updated');
    }
}
