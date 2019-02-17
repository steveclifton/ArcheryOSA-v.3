<?php

namespace App\Http\Controllers;

use App\User;
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

    public function createBasicUser($validated, $parentuserid = null)
    {
        if (empty($validated)) {
            return false;
        }

        $user = new User();
        $user->firstname = strtolower($validated['firstname']);
        $user->lastname  = strtolower($validated['lastname']);
        $user->email     = !empty($validated['email']) ? $validated['email'] : $this->createHash(12);
        $user->roleid    = 4;
        $user->username  = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $validated['firstname'].$validated['lastname'])) . rand(1,1440);
        $user->password  = $this->createHash(12);
        $user->parentuserid = $parentuserid;
        $user->membership  = strtolower($validated['membership']);
        $user->dateofbirth  = $validated['dateofbirth'];
        $user->save();

        return $user->userid;
    }

}






