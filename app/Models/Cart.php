<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'persistentcart';
    protected $primaryKey = 'cartid';

    /**
     * Add an entry to the cart
     * @param $cartitem
     * @return bool
     */
    public function addEntryCartItem($cartitem)
    {
        $cartitems = $this->getCartItems();

        if (empty($cartitem->entryid)) {
            return false;
        }

        if (is_array($cartitems)) {
            $cartitems[$cartitem->entryid] = $cartitem;
        }
        else if (empty($cartitems)) {
            $cartitems = [];
            $cartitems[$cartitem->entryid] = $cartitem;
        }

        $this->items = json_encode($cartitems);

        $this->save();

        $this->updateCartTotal();

        return true;
    }

    /**
     * Returns a copy of the cart items
     * @return array
     */
    public function getCartItems()
    {
        $cartitems = json_decode($this->items);

        return !empty($cartitems) ? (array) $cartitems : [];
    }

    /**
     * Remove an entry from the cart
     * @param $entryid
     * @return bool
     */
    public function removeEntryCartItem($entryid)
    {
        $cartitems = $this->getCartItems();

        if (empty($cartitems)) {
            return false;
        }

        // if is an array and the item is in there (key'd by entryid)
        // - remove
        if (is_array($cartitems) && isset($cartitems[$entryid])) {
            unset($cartitems[$entryid]);
        }

        $this->items = json_encode($cartitems);

        $this->save();

        $this->updateCartTotal();

        return true;
    }

    /**
     * Update the Carts total
     * @return float|int|mixed|null
     */
    protected function updateCartTotal()
    {
        $cartitems = $this->getCartItems();

        if (empty($cartitems) || !is_array($cartitems)) {
            return null;
        }

        $total = 0;
        foreach ($cartitems as $cartitem) {
            if (empty($cartitem->total)) {
                continue;
            }

            $total += (float) $cartitem->total;
        }

        $this->total = $total;

        $this->save();

        return $this->total;
    }




}
