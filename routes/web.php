<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//admin routes


Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();




Route::middleware(['web'])->group(function() {

//    Route::middleware(['guest'])->group(function () {
//    });

    Route::middleware(['auth'])->group(function () {

    });

    Route::middleware(['admin'])->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        //Route::get('/admin/test/createsiteroles', 'Admin\TestController@createsiteroles');


    });




});
