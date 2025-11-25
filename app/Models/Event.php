<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'eventid';

    public function isEvent()
    {
        return $this->eventtypeid == 1;
    }

    public function isLeague()
    {
        return $this->eventtypeid == 2;
    }

    public function isPostal()
    {
        return $this->eventtypeid == 3;
    }

    public function isNonShooting()
    {
        return $this->eventtypeid == 4;
    }

    public function canBeEntered() : bool
    {
        return in_array($this->eventstatusid, [1], true);
    }

    public function canEnterEvent()
    {
        // Stop here if its not Open
        if (!$this->canBeEntered()) {
            return false;
        }

        // check only for events, LEAGUE is different
        if (!empty($this->attributes['visible']) && $this->isEvent() && $this->eventstatusid === 1) {

            // get the eventcomps, if empty, false
            $eventcompetitions = EventCompetition::where('eventid', $this->eventid)->get()->first();
            if (empty($eventcompetitions)) {
                return false;
            }


            // if we are over entry limit
            $entrycount = DB::table('evententrys')
                            ->where('eventid', $this->eventid)
                            ->count();

            if (!empty($this->entrylimit) && $entrycount >= $this->entrylimit) {
                return false;
            }

            // set the date default to be nz
            date_default_timezone_set('Pacific/Auckland');

            // if we have an entry close set, do checks
            if (!empty($this->entryclose)) {

                if (time() > strtotime($this->entryclose . ' + 1 day') // if entry close is after closing date
                    ||
                    (strtotime($this->entryclose) > strtotime($this->start) + 86400) // if the entry close is after the start date + 1 day
                ) {
                    return false;
                }

            }


            // make it based off the end date + 1day, not start date. Allows people to join on the day until the end
            if (time() > (strtotime($this->end) + 60*60*24) ) {
                return false;
            }

            return true;
        }


        return false;
    }

    public function canEnterLeague()
    {
        // make it based off the end date + 1day, not start date. Allows people to join on the day until the end
        if (time() > (strtotime($this->end) + 60*60*24) ) {
            return false;
        }

        return (!empty($this->attributes['visible']) && $this->eventstatusid === 1);
    }

    public function canEnterNonShooting()
    {

        if (!empty($this->attributes['visible']) && $this->eventstatusid === 1) {
            // if we are over entry limit
            $entrycount = DB::table('evententrys')
                ->where('eventid', $this->eventid)
                ->count();

            if (!empty($this->entrylimit) && $entrycount >= $this->entrylimit) {
                return false;
            }

            // set the date default to be nz
            date_default_timezone_set('Pacific/Auckland');

            // if we have an entry close set, do checks
            if (!empty($this->entryclose)) {

                if (time() > (strtotime($this->entryclose)) // if entry close is after closing date
                    ||
                    (strtotime($this->entryclose) > strtotime($this->start) + 86400) // if the entry close is after the start date + 1 day
                ) {
                    return false;
                }

            }


            // make it based off the end date + 1day, not start date. Allows people to join on the day until the end
            if (time() > (strtotime($this->end) + 60 * 60 * 24)) {
                return false;
            }

            return true;

        }

        return false;
    }


    public function getEventsDateRange()
    {
        $start = new \DateTime( $this->start );
        $end = new \DateTime( $this->end );
        $end = $end->modify( '+1 day' );
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($start, $interval ,$end);

        return $daterange;
    }

    public function isArcheryNZ() : bool
    {
        return $this->organisationid == 1;
    }

    public function isVisible()
    {
        return $this->visible == 1;
    }
}
