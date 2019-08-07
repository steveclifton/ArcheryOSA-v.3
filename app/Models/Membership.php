<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';
    protected $primaryKey = 'membershipid';

    public function __get($key)
    {
        if ($key == 'organisationname') {
            if (empty($this->organisationid)) {
                return 'None';
            }

            return Organisation::where('organisationid', $this->organisationid)->pluck('label')->first();
        }

        return parent::__get($key);
    }
}
