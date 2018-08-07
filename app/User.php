<?php

namespace App;

use App\Models\EntryStatus;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventEntry;
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

    public function getFullname()
    {
        return ucwords($this->firstname ?? '') . " " . ucwords($this->lastname ?? '');
    }



    public function getEventEntry($eventid)
    {
        return EventEntry::where('userid', $this->userid)
                        ->where('eventid', $eventid)
                        ->get()
                        ->first();


    }



    public function getEventEntryStatus($eventid)
    {
        $evententry = $this->getEventEntry($eventid);

        if (empty($evententry)) {
            return false;
        }

        return EntryStatus::where('entrystatusid', $evententry->entrystatusid)->pluck('label')->first();
    }

    public function getEventEntryPaid($eventid)
    {
        $evententry = $this->getEventEntry($eventid);

        if (empty($evententry)) {
            return false;
        }

        return EntryStatus::where('entrystatusid', $evententry->entrystatusid)->pluck('label')->first();
    }

}
