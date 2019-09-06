<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 20/05/18
 * Time: 6:01 AM
 */

namespace App\Jobs;


use Illuminate\Support\Facades\Auth;

class ArcheryOSASender
{
    public function __construct()
    {

    }

    public function checkEmailAddress($email)
    {

        if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            return true;
        }

        return false;
    }

    public function getEmailAddress($email)
    {

        if (getenv('APP_LIVE') == "false") {
            return 'info@archeryosa.com';
        }

        return $email;

    }
}
