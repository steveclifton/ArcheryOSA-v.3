<?php

namespace App\Http\Controllers;

use App\Http\Classes\EventsHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->eventhelper = new EventsHelper();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $upcomingevents = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` + interval 1 day > now() 
            AND `e`.`visible` = 1
            AND `e`.`eventstatusid` NOT IN (3,4)
            ORDER BY `e`.`start`
        ");

        $myevents = [];
        if (Auth::check()) {
            $myevents = DB::select("
                SELECT e.label, e.start, e.eventurl, e.imagedt, es.label as status,e.level, e.region    
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                JOIN `entrystatus` es ON (ee.entrystatusid = es.entrystatusid)
                WHERE `ee`.`userid` = '".Auth::id()."'
                AND `e`.`visible` = 1
                AND `e`.`end` > now()
                ORDER BY e.start
            ");

        }

        return view('home', compact('upcomingevents', 'myevents'));
    }

    public function debug()
    {
        if (Auth::id() == 1) {
            phpinfo(INFO_MODULES);
            die;
        }
        die;
    }

    public function teststripe()
    {
        return view('stripetest');
    }


    public function getPrivacyPolicy()
    {
        return view('static.privacy');
    }
}
