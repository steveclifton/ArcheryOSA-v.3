<?php

namespace App\Http\Controllers;

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
        //$this->middleware('auth');
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
            WHERE `e`.`end` > now()
            AND `e`.`visible` = 1
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");

        $myevents = [];
        if (Auth::check()) {
            $myevents = DB::select("
                SELECT e.label, e.start, e.eventurl, e.imagedt, es.label as status
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                JOIN `entrystatus` es ON (ee.entrystatusid = es.entrystatusid)
                WHERE `ee`.`userid` = '".Auth::id()."'
            ");

        }

        $resultevents = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`start` <= '".date('Y-m-d')."'
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");

        return view('home', compact('upcomingevents', 'myevents', 'resultevents'));
    }
}
