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

Auth::routes();

Route::get('/', 'HomeController@index');




Route::middleware(['web'])->group(function() {

    Route::middleware(['guest'])->group(function () {
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register');
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::get('resetpassword', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    });

    Route::middleware(['auth'])->group(function () {
        // Logout
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');

        // Profile
        Route::get('profile', 'Auth\ProfileController@getDashboard');
        Route::get('profile/mydetails', 'Auth\ProfileController@getMyDetails');
        Route::post('profile/mydetails', 'Auth\ProfileController@updateProfile')->name('updateprofile');

    });

    Route::middleware(['admin'])->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        // Clubs
        Route::get('admin/clubs', 'Admin\ClubController@get');
        Route::get('admin/clubs/create', 'Admin\ClubController@getCreateView');

        // Divisions
        Route::get('admin/divisions', 'Admin\DivisionController@get');
        Route::get('admin/divisions/create', 'Admin\DivisionController@getCreateView');

        // Organisations
        Route::get('admin/organisations', 'Admin\OrganisationController@get');
        Route::get('admin/organisations/create', 'Admin\OrganisationController@getCreateView');

        // Rounds
        Route::get('admin/rounds', 'Admin\RoundController@get');
        Route::get('admin/rounds/create', 'Admin\RoundController@getCreateView');

        // Tournaments
        Route::get('admin/tournaments', 'Admin\TournamentController@get');
        Route::get('admin/tournaments/create', 'Admin\TournamentController@getCreateView');


    });




});
