<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class ReportController extends Controller
{
    public function exportJulySummary(Request $request)
    {
        $date = date('Y-m-d', strtotime('July 1st last year'));


        $entries = DB::select("
            SELECT `e`.`label`, `ee`.`entryid`, `ee`.`gender`, `e`.`start`
            FROM `evententrys` ee
            JOIN `events` e USING (`eventid`)
            WHERE `eventid` IN (SELECT `eventid` FROM `events` WHERE `start` >= :date)
            AND `ee`.`entrystatusid` = 2 
            ORDER BY `e`.`start`
        ", ['date' => $date]);

        $sorted = [];

        foreach ($entries as $entry) {
            $sorted[$entry->label . '|' . $entry->start][$entry->gender][] = $entry->entryid;
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Event', 'Start Date', 'Male', 'Female']);

        $f = 0;
        $m = 0;
        foreach ($sorted as $namedate => $genders) {

            list($name, $date) = explode('|', $namedate);

            $female = isset($genders['f']) ? count($genders['f']) : 0;
            $male = isset($genders['m']) ? count($genders['m']) : 0;

            $csv->insertOne([$name, $date, $male, $female]);

            $m += $male;
            $f += $female;
        }

        $csv->insertOne(['Total', 'Total', $m, $f]);


        $csv->output('JulySummary.csv');
        die;

    }
}
