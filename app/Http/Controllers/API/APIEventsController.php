<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class APIEventsController extends Controller
{

    /**
     * Gets all upcoming events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingEvents()
    {
        $events = DB::select("
            SELECT e.eventid, e.label as eventname, e.eventtypeid, e.entryclose, e.start, e.end, e.daycount, 
             e.contactname, e.phone, e.email, e.location, e.cost, e.bankaccount, e.bankreference, e.schedule, e.info,
              e.eventurl, e.clubid, e.organisationid, e.entrylimit,  es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` > NOW()
            AND `e`.`visible` = 1
            ORDER BY IFNULL(e.entryclose, e.start) 
        ");

        foreach ($events as $event) {
            $event->eventurl = makeEventDetailsUrl($event->eventurl);
        }

        return response()->json([
            'success' => true,
            'date' => [
                'events' => $events
            ]
        ]);
    }


    /**
     * Gets all previous events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousEvents()
    {
        $events = DB::select("
            SELECT e.eventid, e.label as eventname, e.eventtypeid, e.entryclose, e.start, e.end, e.daycount, 
              e.contactname, e.phone, e.email, e.location, e.cost, e.bankaccount, e.bankreference, e.schedule, e.info,
              e.eventurl, e.clubid, e.organisationid, e.entrylimit,  es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` < NOW()
            AND `e`.`visible` = 1
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");


        foreach ($events as $event) {
            $event->eventurl = makeEventDetailsUrl($event->eventurl);
        }
        
        return response()->json([
            'success' => true,
            'date' => [
                'events' => $events
            ]
        ]);
    }
}
