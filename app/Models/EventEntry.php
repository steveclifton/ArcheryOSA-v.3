<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventEntry extends Model
{
    protected $table = 'evententrys';
    protected $primaryKey = 'entryid';

    public function entrycompetitions()
    {
        return EntryCompetition::where('entryid', $this->entryid)->get();
    }

}
