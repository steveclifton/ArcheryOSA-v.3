<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 20/05/18
 * Time: 6:01 AM
 */

namespace App\Jobs;


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
}