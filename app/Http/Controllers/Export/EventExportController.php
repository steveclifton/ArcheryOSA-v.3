<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Events\Auth\EventTargetAllocationController;
use App\Http\Controllers\Events\PublicEvents\EventResultsController;
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
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

        return view('events.auth.management.exports', compact('event', 'eventcompetitions'));
    }

    public function exportevententries_ianseo(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            die();
        }

        // Get Event
        if (!Auth::user()->canEditEvent($event->eventid)) {
            die;
        }

        $eventcompetitionids = EventCompetition::where('eventid', $event->eventid)->pluck('eventcompetitionid')->toArray();

        $entries = DB::select("
            SELECT ee.bib, 
                   1 as `session`, 
                   d.class as division, 
                   d.age as class, 
                   ta.target as target, 
                   ee.individualqualround, 
                   ee.teamqualround, 
                   ee.individualfinal, 
                   ee.teamfinal,
                   ee.mixedteamfinal,
                   ee.lastname,
                   ee.firstname, 
                   ee.gender,
                   ee.country as countrycode, 
                   (SELECT `name` FROM `countries` WHERE `iso_3166_3` = ee.country) as country,
                   DATE_FORMAT(str_to_date(ee.dateofbirth, '%d-%m-%Y'),'%Y-%m-%d') as dateofbirth,
                   ee.subclass,
                   c.description as clubcode,
                   c.label as clubname
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `targetallocations` ta ON (ee.userid = ta.userid AND ec.eventcompetitionid = ta.eventcompetitionid AND ec.divisionid = ta.divisionid)
            LEFT JOIN `clubs` c ON (ee.clubid = c.clubid)
            WHERE `ee`.`eventid` = :eventid
            AND `ec`.`eventcompetitionid` IN (".implode(',', (array) $eventcompetitionids).")
            GROUP BY ee.userid, ec.eventcompetitionid, ec.divisionid
            ORDER BY `d`.label, ee.firstname
        ", ['eventid' => $event->eventid]);

        foreach ($entries as $entry) {

            if ($entry->gender == 'f') {
                $entry->gender = 'w';
            }

            $entry->firstname = $this->ucname($entry->firstname);
            $entry->lastname  = $this->ucname($entry->lastname);

        }

        $filename = str_replace(' ', '-', $event->label) .'-' . date('d-m', time());
        switch($request->type) {
            case 'csv':
                $csv = Writer::createFromFileObject(new \SplTempFileObject());

                $csv->insertOne(['Bib', 'Session', 'Division', 'Class', 'Target', 'IndividualQualRound', 'TeamQualRound',
                    'IndividualFinal', 'TeamFinal', 'MixedTeamFinal', 'Lastname', 'Firstname', 'Gender', 'Country Code', 'Country', 'DOB', 'Subclass', 'Clubcode', 'Clubname' ]);

                foreach ($entries as $entry) {
                    $csv->insertOne(array_map('ucwords', (array) $entry));
                }

                $csv->output( $filename . '.csv');
                die;

            case 'pdf':

                $mpdf = new Mpdf(['orientation' => 'L', 'tempDir' => __DIR__ . '/tmp']);
                $mpdf->WriteHTML($this->makeentrypdfmarkup($event->label, $entries));
                $mpdf->Output($filename . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                die;
        }

    }

    public function exportevententries(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

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

        if ($event->isNonShooting()) {
            $entries = DB::select("
                SELECT ee.firstname, ee.lastname, '' as clubname, ee.email, ee.address, ee.phone, 
                        '' as divisionname, '' as roundname, ee.membership, ee.paid, ee.gender, ee.notes, ee.created_at, ee.updated_at
                FROM `evententrys` ee
                WHERE `ee`.`eventid` = :eventid
                ORDER BY  ee.firstname
            ", ['eventid' => $event->eventid]);
        }
        else {
            $eventcompetitionids = EventCompetition::where('eventid', $event->eventid)->pluck('eventcompetitionid')->toArray();

            $entries = DB::select("
                SELECT ee.firstname, ee.lastname, c.label as clubname, ee.email, ee.address, ee.phone, 
                        ecom.label as `eventcompname`, d.label as divisionname, r.label as roundname, ee.membership, ee.paid, ee.gender, ee.notes, ee.bib, ee.created_at, ee.updated_at
                FROM `evententrys` ee
                JOIN `entrycompetitions` ec USING (`entryid`)
                JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
                JOIN `rounds` r ON (ec.roundid = r.roundid)
                JOIN `eventcompetitions` ecom ON ec.eventcompetitionid = ecom.eventcompetitionid
                LEFT JOIN `clubs` c ON (ee.clubid = c.clubid)
                WHERE `ee`.`eventid` = :eventid
                AND `ec`.`eventcompetitionid` IN (".implode(',', (array)$eventcompetitionids).")
                ORDER BY `d`.label, ee.firstname
            ", ['eventid' => $event->eventid]);
        }

        foreach ($entries as $entry) {
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

                $csv->insertOne(['Firstname', 'Lastname', 'Club', 'Email', 'Address', 'Phone', 'Competition', 'Division',
                    'Round Name', 'Membership', 'Paid Status', 'Gender', 'Notes', 'Bib', 'Created Date', 'Updated Date' ]);

                foreach ($entries as $entry) {
                    $csv->insertOne((array) $entry);
                }

                $csv->output( $filename . '.csv');
                die;

            case 'pdf':

                $mpdf = new Mpdf(['orientation' => 'L', 'tempDir' => __DIR__ . '/tmp']);
                $mpdf->WriteHTML($this->makeentrypdfmarkup($event->label, $entries));
                $mpdf->Output($filename . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                die;
        }

    }

    public function exportEventScores(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event) || empty($request->eventcompetitionid)) {
            die();
        }

        $results = DB::select("
                SELECT ee.firstname, ee.lastname, ee.gender, ee.membership, ec.entrycompetitionid, 
                    ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit,
                    sf.*, c.label as clubname
                FROM `evententrys` ee
                JOIN `entrycompetitions` ec USING (`entryid`)
                JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
                JOIN `rounds` r ON (ec.roundid = r.roundid)
                LEFT JOIN `clubs` c ON (ee.clubid = c.clubid)
                JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
                WHERE `ee`.`eventid` = '".$event->eventid."'
                AND `ec`.`eventcompetitionid` = :eventcompetitionid
                AND `ee`.`entrystatusid` = 2
                ORDER BY `d`.label
            ", ['eventcompetitionid' => $request->eventcompetitionid]);

        $newresults = [];
        foreach ($results as $entry) {
            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            $newresults[$entry->bowtype][$gender . $entry->divisionname][] = $entry;

            if (!empty($apicall)) {
                unset($entry->userid);
            }
        }

        $filename = str_replace(' ', '-', $event->label) .'-' . date('d-m', time());
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Firstname', 'Lastname', 'Club', 'Division', 'Membership', 'Gender', 'Dist1', 'Dist2', 'Dist3', 'Dist4', 'Total', 'Hits', '10', 'X']);

        foreach ($newresults as $divisions) {
            foreach ($divisions as $division) {
                foreach ($division as $d) {
                    $arr = [
                        $d->firstname ?? '',
                        $d->lastname ?? '',
                        $d->clubname ?? '',
                        $d->divisionname ?? '',
                        $d->membership ?? '',
                        $d->gender ?? '',
                        $d->dist1score ?? '',
                        $d->dist2score ?? '',
                        $d->dist3score ?? '',
                        $d->dist4score ?? '',
                        $d->total ?? '',
                        $d->totalhits ?? '',
                        $d->inners ?? '',
                        $d->max ?? '',
                    ];
                    $csv->insertOne($arr);
                }

            }
        }

        $csv->output($filename . '.csv');
        die;

    }

    public function exportEventTargetAllocations(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            die();
        }

        $targetallocation = new EventTargetAllocationController();

        $users = $targetallocation->getUsers($event->eventid);

        if (empty($users)) {
            die('No Target Allocations');
        }

        $ta = [];
        foreach ($users as $user) {
            $key = $user->eventcompname . ' - ' . date('d F Y', strtotime($user->eventcompdate));
            $ta[$key][] = $user;
        }


        $htmlarray = [];
        foreach($ta as $eventcompname => $users) {
            $html = '<div><h3>' . $eventcompname . '</h3>';

            $html .= '<table>'.
                        '<thead>'.
                            '<tr>'.
                                '<th>Target</th>'.
                                '<th>Fullname</th>'.
                                '<th>Division</th>'.
                                '<th>Round</th>'.
                            '</tr>'.
                        '</thead>'.
                        '<tbody>';

            foreach ($users as $user) {
                $html .= '<tr>'.
                    '<td>'.$user->target.'</td>'.
                    '<td>'.ucwords(strtolower($user->fullname)).'</td>'.
                    '<td>'.$user->divisionname.'</td>'.
                    '<td>'.$user->roundname.'</td>'.
                    '</tr>';
            }

            $html .= '</tbody></table>';

            $htmlarray[] = $html;

        }

        $style = '<style>
                    table {
                        border-collapse: collapse;
                        font-size: 11px;
                        page-break-inside: avoid;
                    }
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 15px;
                        text-align: left;
                    }
                    </style>';

        $mpdf = new Mpdf(['tempDir' => __DIR__ . '/tmp']);
        $mpdf->WriteHTML($style);

        foreach ($htmlarray as $key => $html) {
            $mpdf->WriteHTML($html);

            if ($key != array_key_last($htmlarray)) {
                $mpdf->AddPage();
            }
        }

        $mpdf->Output('TargetAllocations' . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        die;

    }

    protected function makeentrypdfmarkup($eventname, $entries)
    {
        $html = '<h3>' .$eventname. '</h3>';

        if (empty($entries)) {
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



        foreach ($entries as $entry) {
            $html .= '<tr>'.
                '<td>'.$entry->firstname.'</td>'.
                '<td>'.$entry->lastname.'</td>'.
                '<td>'.($entry->clubname ?? '').'</td>'.
                '<td>'.$entry->address.'</td>'.
                '<td>'.$entry->phone.'</td>'.
                '<td>'.($entry->divisionname ?? '').'</td>'.
                '<td>'.($entry->roundname ??'').'</td>'.
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
