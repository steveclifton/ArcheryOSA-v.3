<?php

namespace App\Models\Traits;


use App\Models\Cart;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventEntry;

trait UserCart
{
    /**
     * Loads the users cart
     * @return Cart
     */
    protected function loadcart()
    {
        $this->cart = Cart::where('userid', $this->userid)->first();

        if (empty($this->cart)) {
            $cart = new Cart();
            $cart->userid = $this->userid;
            $cart->total = 0;
            $cart->items = json_encode([]);
            $cart->save();

            $this->cart = $cart;
        }

        return $this->cart;
    }

    /**
     * Gets the users Cart
     * @return mixed
     */
    public function getcart()
    {
        if (empty($this->cart)) {
            $this->loadcart();
        }

        return $this->cart;
    }

    /**
     * Returns an array of the cart items
     * @return array
     */
    public function getcartitems()
    {
        if (empty($this->cart)) {
            $this->loadcart();
        }
        return $this->cart->getCartItems();
    }

    /**
     *
     * @param Event $event
     * @param EventEntry $entry
     * @param array $entryCompetitions
     * @return bool
     */
    public function addentrycartitem(Event $event, EventEntry $entry)
    {
        $this->loadcart();

        if (empty($event) || empty($entry)) {
            return false;
        }

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

        // Get all the entry competitions
        $entrycompetitions = EntryCompetition::where('entryid', $entry->entryid)->get();
        $entrycomps = [];
        foreach ($entrycompetitions as $entrycompetition) {
            $entrycomps[$entrycompetition->eventcompetitionid] = $entrycompetition;
        }

        $cartitem = new \stdClass();
        $cartitem->userid = $entry->userid;
        $cartitem->entryid = $entry->entryid;
        $cartitem->eventid = $entry->eventid;
        $cartitem->eventname = $event->label;
        $cartitem->username = $entry->firstname . ' ' . $entry->lastname;
        $cartitem->eventcompetitions = $this->getevententrycomplabels($event, $entrycomps, $eventcompetitions);
        $cartitem->total = $this->getevententrytotalcost($event, $entrycomps, $eventcompetitions);

        return $this->cart->addEntryCartItem($cartitem);

    }

    public function removeEntryFromCart($entryid)
    {
        $this->loadcart();

        if (empty($entryid)) {
            return false;
        }

        return $this->cart->removeEntryCartItem($entryid);
    }

    public function getevententrytotalcost(Event $event, array $entryCompetitions, $eventcompetitions)
    {
        $totalcost = 0;

        if (count($eventcompetitions) == count($entryCompetitions)) {
            // Then number of entrys is the same as the number of competitions, so use total event
            $totalcost = $event->totalcost;
        }
        else {
            // Need to loop through the eventcomps and see what is entered
            foreach ($eventcompetitions as $eventcomp) {
                if (!empty($entryCompetitions[$eventcomp->eventcompetitionid])) {
                    $entry = $entryCompetitions[$eventcomp->eventcompetitionid];
                    $totalcost += (float) $entry->cost;
                }
            }
        }

        return $totalcost;
    }

    public function getevententrycomplabels(Event $event, array $entryCompetitions, $eventcompetitions)
    {
        $complabels = [];

        // Need to loop through the eventcomps and see what is entered
        foreach ($eventcompetitions as $eventcomp) {
            if (!empty($entryCompetitions[$eventcomp->eventcompetitionid])) {
                $complabels[$eventcomp->eventcompetitionid] = $eventcomp->label;
            }
        }

        return $complabels;
    }

}