<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\EntryCompetition;
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

        $leagueweeks = $eventcompetition = null;
        if ($event->isleague()) {
            $leagueweeks = ceil($event->daycount / 7);
            $eventcompetition = EventCompetition::where('eventid', $event->eventid)->first();
        }

        $entry = EntryCompetition::where('eventid', $event->eventid)->first();

        return view('events.auth.management.settings', compact('entry', 'event', 'eventstatuses', 'leagueweeks', 'eventcompetition'));
    }

    public function updateEventSettings(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Invalid');
        }

        if (empty(Auth::user()->isSuperAdmin())) {
            $eventadmin = EventAdmin::where('userid', Auth::id())
                ->where('eventid', $event->eventid)
                ->where('canedit', 1)
                ->first();

            if (empty($eventadmin)) {
                return back()->with('failure', 'Cannot edit event');
            }
        }


        if ($event->eventypeid != 4 && !empty($request->input('visible'))) {
            $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get()->first();

            if (empty($eventcompetitions)) {
                return back()
                    ->with('failure', 'Event must have competitions before it can be active')
                    ->with('visible', true);
            }
        }

        if (!empty($request->hasFile('filename'))) {
            $file = $request->file('filename');

            // clean up the old one
            if (!empty($event->filename) && is_file(public_path('files/events/' . $event->filename))) {
                unlink(public_path('files/events/' . $event->filename));
            }

            @list($fileName, $fileExt) = explode('.', $file->getClientOriginalName());

            $filename = $fileName .'-' . date('d-h-m') . '.' . $file->getClientOriginalExtension();

            // save the file
            $file->move('files/events', $filename);
            $event->filename = $filename;

        }


        if (!empty($request->hasFile('imagedt'))) {

            //clean up old image
            if (!in_array(($event->imagedt ?? ''), ['event1.jpg', 'event2.jpg'])) {
                if (is_file(public_path('images/events/' . $event->imagedt))) {
                    unlink(public_path('images/events/' . $event->imagedt));
                }
            }

            $image = $request->file('imagedt');

            // Create for cards
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/events/' . $filename);
            Image::make($image)->resize(1024, 641)->save($location);
            $event->imagedt = $filename;

        }

        if (!empty($request->hasFile('imagebanner'))) {

            //clean up old image
            if (!in_array(($event->imagebanner ?? ''), ['event1.jpg', 'event2.jpg'])) {
                if (is_file(public_path('images/events/' . $event->imagebanner))) {
                    unlink(public_path('images/events/' . $event->imagebanner));
                }
            }

            $image = $request->file('imagebanner');

            // Create for cards
            $filename = time() . rand(0,999) . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/events/' . $filename);
            Image::make($image)->resize(1471, 200)->save($location);
            $event->imagebanner = $filename;
        }

        $event->adminnotifications = empty($request->input('adminnotifications')) ? 0 : 1;
        $event->eventstatusid      = intval($request->input('eventstatusid'));
        $event->entrylimit         = empty($request->input('entrylimit'))          ? NULL : intval($request->input('entrylimit'));
        $event->visible            = !empty($request->input('visible'))            ? 1 : 0;
        $event->showoverall        = !empty($request->input('showoverall'))        ? 1 : 0;
        $event->multipledivisions  = !empty($request->input('multipledivisions'))  ? 1 : 0;
        $event->dateofbirth        = !empty($request->input('dateofbirth'))        ? 1 : 0;
        $event->clubrequired       = !empty($request->input('clubrequired'))       ? 1 : 0;
        $event->pickup             = !empty($request->input('pickup'))             ? 1 : 0;
        $event->schoolrequired     = !empty($request->input('schoolrequired'))     ? 1 : 0;
        $event->mqs                = !empty($request->input('mqs'))                ? 1 : 0;
        $event->waver              = !empty($request->input('waver'))              ? 1 : 0;
        $event->wavermessage       = $request->input('wavermessage');
        $event->membershiprequired = !empty($request->input('membershiprequired')) ? 1 : 0;
        $event->filename           = (!empty($event->filename) && !empty($request->input('removefile'))) ? NULL : $event->filename;
        $event->save();


        $eventcompetition = EventCompetition::where('eventid', $event->eventid)->first();

        if (!empty($eventcompetition)) {
            $eventcompetition->currentweek   = !empty($request->input('currentweek')) ? intval($request->input('currentweek')) : 1;
            $eventcompetition->save();
        }

        return back()->with('success', 'Event updated');
    }
}
