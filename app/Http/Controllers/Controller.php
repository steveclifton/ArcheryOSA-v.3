<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function createHash()
    {
        return substr(md5(time() . rand(0, 999)), 0, 5);
    }


    public function prepurl($url)
    {
        $url = str_replace(['+', ' '], '-', strtolower(strval($url)));
        $url = preg_replace(['/[^0-9a-z\-]/', '/-+/'], ['', '-'], $url);
        return trim($url, '-');
    }
}
