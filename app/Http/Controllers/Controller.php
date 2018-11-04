<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function createHash($length = 5)
    {
        return substr(md5(time() . rand(0, 999)), 0, $length);
    }

    public function isLive()
    {
        return getenv('APP_LIVE');
    }

    public function getcacheditem($key)
    {
        if (isset($_GET['nocache'])) {
            return false;
        }

        return Cache::get($key);
    }


    public function userOk($eventurl)
    {
        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
                        SELECT e.*, es.label as status
                        FROM `events` e
                        JOIN `eventstatus` es USING (`eventstatusid`)
                        WHERE `e`.`eventurl` = :eventurl
                        LIMIT 1
        ",['eventurl' => $eventurl]);
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
        ", ['userid' => Auth::id(), 'eventurl' => $eventurl]);
        }

        $event = !empty($event) ? reset($event) : null;

        return $event;
    }


}






