<?php

namespace App\Http\Controllers\Vue\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\EntryStatus;
use App\Models\Event;
use App\Models\EventEntry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{

    protected Collection $divisions;
    protected Collection $entrystatus;

    public function getAllEvents()
    {

        // check to see they are auth level first
        if (!Auth::user()->isEventAdmin()) {
            return abort(403);
        }

        if (Auth::user()->isSuperAdmin()) {
            return DB::select("
                SELECT e.eventid, e.start, e.end, e.label, e.status, e.visible, count(ee.entryid) as entries, e.eventurl
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                GROUP BY `e`.`eventid`
                ORDER BY `e`.`start` DESC
            ");
        }

        return DB::select("
                SELECT e.eventid, e.start, e.end, e.label, e.status, e.visible, count(ee.entryid) as entries, e.eventurl
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                JOIN `eventadmins` ea USING (`eventid`)
                WHERE `ea`.`userid` = :userid
                GROUP BY `e`.`eventid`
                ORDER BY `e`.`start` DESC
        ", ['userid' => Auth::id()]);

    }

    public function getEventDetails(Request $request)
    {
        if (empty($request->eventUrl)) {
            return abort(400);
        }

        $event = Event::where('eventurl', $request->eventUrl)->first();

        if (empty($event)) {
            return abort(404);
        }

        $this->divisions = Division::all()->keyBy('divisionid');
        $this->entrystatus = EntryStatus::all()->keyBy('entrystatusid');

        $entries = EventEntry::where('eventid', $event->eventid)->get();

        return [
            'event' => [
                'name' => $event->label,
                'entries' => array_map([$this, 'formatListEntry'], $entries->toArray())
            ]
        ];

    }

    private function formatListEntry($entry)
    {
        return [
            'entryid' => $entry['entryid'],
            'bib' => $entry['bib'],
            'name' => ucwords(strtolower($entry['firstname'] . ' ' . $entry['lastname'])),
            'paid' => $entry['paid'],
            'notes' => $entry['notes'],
            'email' => $entry['email'],
            'gender' => $entry['gender'] == 'f' ? 'Womens' : 'Mens',
            'status' => isset($this->entrystatus[$entry['entrystatusid']]) ? $this->entrystatus[$entry['entrystatusid']]->label : null,
            'created' => date('Y-m-d', strtotime($entry['created_at'])),
            'division' => isset($this->divisions[$entry['divisionid']]) ? $this->divisions[$entry['divisionid']]->label : null,
            'entrystatusid' => $entry['entrystatusid'],
            'confirmationemail' => $entry['confirmationemail'],
        ];
    }
}
