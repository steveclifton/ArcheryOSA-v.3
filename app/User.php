<?php

namespace App;

use App\Models\Cart;
use App\Models\EntryStatus;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventEntry;
use App\Models\Membership;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
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

        if ($this->isSuperAdmin()) {
            return true;
        }

        return EventAdmin::where('userid', Auth::id())
                    ->where('eventid', $eventid)
                    ->where('canedit', 1)
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

    public function isSuperAdmin()
    {
        return $this->roleid === 1;
    }

    public function getUserType()
    {
        switch ($this->roleid) {
            case '1':
                return 'Super Admin';
            case '2':
                return 'Admin';
            case '3':
                return 'Event Admin';

            default:
                return 'User';
        }
    }

    public function getMemberships()
    {
        return Membership::where('userid', $this->userid)->get();
    }


    public function getChildren()
    {
        return User::where('parentuserid', $this->userid)->get();
    }

    protected function loadcart()
    {
        $this->cart = Cart::where('userid', $this->userid)->first();
    }

    public function getcart()
    {
        if (empty($this->cart)) {
            $this->loadcart();
        }

        return $this->cart;
    }

    public function getcartitems()
    {
        if (empty($this->cart)) {
            $this->loadcart();
        }
        $items = [];

        if (!empty($this->cart->items)) {
            $items = (array) json_decode($this->cart->items);
        }

        return $items;
    }

    public function getParent()
    {
        return User::where('userid', $this->parentuserid)->first();
    }
}
