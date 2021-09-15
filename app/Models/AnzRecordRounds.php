<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnzRecordRounds extends Model
{
    use HasFactory;
    protected $table = 'anz_rounds';
    protected $primaryKey = 'id';
}
