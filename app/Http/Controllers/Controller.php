<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

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


}
