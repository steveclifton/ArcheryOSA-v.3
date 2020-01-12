<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'eventid';

    const MIN_ENTRY_COST = '5';

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

    public function canEnterEvent()
    {

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
            date_default_timezone_set('NZ');

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
            date_default_timezone_set('NZ');

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

    public function getEventCompetitionCosts()
    {
        $eventcompetitions = EventCompetition::where('eventid', $this->eventid)->get();

        $total = 0;
        foreach ($eventcompetitions as $eventcompetition) {
            if (empty((int) $eventcompetition->cost)) {
                return false;
            }
            $total += $eventcompetition->cost;
        }

        return $total;
    }

    public function canUseCC()
    {
        $eventcomptotal = $this->getEventCompetitionCosts();

        // Some payment providers have a min cost, make sure its more than that
        if ($eventcomptotal < self::MIN_ENTRY_COST || $this->totalcost < self::MIN_ENTRY_COST) {
            return false;
        }

        return true;
    }
}
