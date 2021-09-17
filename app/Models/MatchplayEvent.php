<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchplayEvent extends Model
{
    use HasFactory;

    protected $table = 'matchplay_event';

    public function getEvent()
    {
        return $this->belongsTo(Event::class, 'eventid', 'eventid')->first();
    }

    public function getDivision()
    {
        return $this->hasOne(Division::class, 'divisionid', 'divisionid')->first();
    }

    public function getRound()
    {
        return $this->hasOne(Round::class, 'roundid', 'roundid')->first();
    }

    public function getGenderAttribute($value) : string
    {
        switch ($value) {
            case 'm':
                return 'Mens';
            case 'f':
                return 'Womens';
        }

        return 'Open';
    }

    public function getRoundGenderLabel()
    {
        return sprintf(
            '%s %s',
            $this->gender,
            $this->getRound()->label
        );
    }

}
