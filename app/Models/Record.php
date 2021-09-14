<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';
    protected $primaryKey = 'recordid';

    protected $fillable = [
        'round',
        'firstname',
        'lastname',
        'club',
        'division',
        'score',
        'xcount',
        'date',
        'bowtype',
        'type',
        'group'
    ];
}
