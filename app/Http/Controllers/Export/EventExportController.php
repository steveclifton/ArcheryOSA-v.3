<?php

namespace App\Http\Controllers\Export;

use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Mpdf\Mpdf;

class EventExportController extends Controller
{

    public function getExportView(Request $request)
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

        return view('events.auth.management.exports', compact('event'));
    }

    public function exportevententries(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            die();
        }

        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $eventauth = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
        }
        else {
            $eventauth = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);
        }

        // cannot access if not approved
        if (empty($eventauth)) {
            die();
        }

        $eventcompetitionids = EventCompetition::where('eventid', $event->eventid)->pluck('eventcompetitionid')->toArray();

        $entrys = DB::select("
            SELECT ee.firstname, ee.lastname, c.label as clubname, ee.email, ee.address, ee.phone, 
                    d.label as divisionname, r.label as roundname, ee.membership, ee.paid, ee.gender, ee.notes, ee.created_at, ee.updated_at
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `clubs` c ON (ee.clubid = c.clubid)
            WHERE `ee`.`eventid` = :eventid
            AND `ec`.`eventcompetitionid` IN (".implode(',', (array)$eventcompetitionids).")
            ORDER BY `d`.label, ee.firstname
        ", ['eventid' => $event->eventid]);

        foreach ($entrys as $entry) {
            if (empty($entry->paid)) {
                $entry->paid = 'No';
            }
            else {
                $entry->paid = 'Yes';
            }

            $entry->created_at = date('d-m-Y', strtotime($entry->created_at));
            $entry->updated_at = date('d-m-Y', strtotime($entry->updated_at));

        }

        $filename = str_replace(' ', '-', $event->label) .'-' . date('d-m', time());
        switch($request->type) {
            case 'csv':
                $csv = Writer::createFromFileObject(new \SplTempFileObject());

                $csv->insertOne(['Firstname', 'Lastname', 'Club', 'Email', 'Address', 'Phone', 'Division',
                    'Round Name', 'Membership', 'Paid Status', 'Gender', 'Notes', 'Created Date', 'Updated Date' ]);

                foreach ($entrys as $entry) {
                    $csv->insertOne((array) $entry);
                }

                $csv->output( $filename . '.csv');
                die;

            case 'pdf':

                $mpdf = new Mpdf(['orientation' => 'L', 'tempDir' => __DIR__ . '/tmp']);
                $mpdf->WriteHTML($this->makeentrypdfmarkup($event->label, $entrys));
                $mpdf->Output($filename . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                die;
        }

    }


    private function makeentrypdfmarkup($eventname, $entrys)
    {
        $html = '<h3>' .$eventname. '</h3>';

        if (empty($entrys)) {
            return $html;
        }

        $html .= '<table>'.
                    '<thead>'.
                        '<tr>'.
                        '<th>Firstname</th>'.
                        '<th>Lastname</th>'.
                        '<th>Club</th>'.
                        '<th>Address</th>'.
                        '<th>Phone</th>'.
                        '<th>Division</th>'.
                        '<th>Round</th>'.
                        '<th>Membership</th>'.
                        '<th>Paid Status</th>'.
                        '<th>Gender</th>'.
                        '<th>Notes</th>'.
                        '<th>Created Date</th>'.
                        '<th>Updated Data</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';



        foreach ($entrys as $entry) {
            $html .= '<tr>'.
                        '<td>'.$entry->firstname.'</td>'.
                        '<td>'.$entry->lastname.'</td>'.
                        '<td>'.$entry->clubname.'</td>'.
                        '<td>'.$entry->address.'</td>'.
                        '<td>'.$entry->phone.'</td>'.
                        '<td>'.$entry->divisionname.'</td>'.
                        '<td>'.$entry->roundname.'</td>'.
                        '<td>'.$entry->membership.'</td>'.
                        '<td>'.$entry->paid.'</td>'.
                        '<td>'.$entry->gender.'</td>'.
                        '<td>'.$entry->notes.'</td>'.
                        '<td>'.$entry->created_at.'</td>'.
                        '<td>'.$entry->updated_at.'</td>'.
                    '</tr>';

        }


        $html .= '</tbody></table>';

        $html .= '<style>
                    table {
                        border-collapse: collapse;
                        font-size: 11px;
                    }
                    
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 15px;
                        text-align: left;
                    }

                    </style>';

        return $html;
    }
}
