<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EventCompetition extends Model
{
    protected $table = 'eventcompetitions';
    protected $primaryKey = 'eventcompetitionid';

    public function getEvent()
    {
        return $this->belongsTo(Event::class, 'eventid', 'eventid')->first();
    }


    public function getEventCompetitionDivisions()
    {
        return Division::whereIn('divisionid', json_decode($this->divisionids))->get();
    }

    public function getPrettyDate()
    {
        return date('d M', strtotime($this->date));
    }
}
