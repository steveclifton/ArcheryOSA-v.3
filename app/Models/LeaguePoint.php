<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaguePoint extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'leaguepoints';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'leaguepointid';
}
