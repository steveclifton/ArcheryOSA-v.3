<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('upcomingevents', 'API\APIEventsController@getUpcomingEvents');

Route::get('previousevents', 'API\APIEventsController@getPreviousEvents');

Route::get('event/{eventurl}/{competitionid?}', 'API\APIEventsController@getEventResults');

