<?php

namespace App;

use App\Models\Event;
use App\Models\EventAdmin;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'userid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'username', 'email', 'password', 'lastipaddress', 'roleid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Need to update this once events are setup, but allows user to see events where they can score
     * @return bool
     */
    public function scoringEnabled()
    {
        return true;
    }

    public function canEditEvent($eventid)
    {
        return EventAdmin::where('userid', Auth::id())
                    ->where('eventid', $eventid)
                    ->where('canedit', 1)
                    ->get()
                    ->first();
    }
}
