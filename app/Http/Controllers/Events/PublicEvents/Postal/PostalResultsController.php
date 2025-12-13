<?php

namespace App\Http\Controllers\Events\PublicEvents\Postal;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventResultService;

class PostalResultsController extends Controller
{

    /**
     * Currently set up to return the same data structure as events
     * @param Event $event
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\stdClass
     */
    public function getOverallResults(Event $event)
    {
        return (new EventResultService())->getOverallResults($event);
    }
}
