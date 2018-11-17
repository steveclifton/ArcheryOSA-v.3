<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'eventid';

    public function isLeague()
    {
        return $this->eventtypeid == 2;
    }

    public function isPostal()
    {
        return $this->eventtypeid == 3;
    }

}
