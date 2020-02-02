<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audit';
    protected $primaryKey = 'auditid';
    protected $fillable = ['eventid', 'userid', 'before', 'after', 'class', 'line', 'method'];
}
