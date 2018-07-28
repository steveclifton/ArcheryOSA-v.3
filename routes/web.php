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


Route::get('/events', 'Events\PublicEvents\EventController@getAllEvents');
Route::get('/events/previous', 'Events\PublicEvents\EventController@getPreviousEvents');

// Define create in public route to show users they can apply to create events
Route::get('/events/create', 'Events\PublicEvents\EventController@createEvent');

// Get specific event details
Route::get('/event/details/{eventurl}', 'Events\PublicEvents\EventController@getEventDetails');

// Results and Ranking
Route::get('/rankings/nz', 'Ranking\RankingController@getCountryRankings');
Route::get('/records/nz', 'Record\RecordController@getCountryRecords');



Route::middleware(['web'])->group(function() {

    Route::middleware(['guest'])->group(function () {
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register');

        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');

        Route::get('passwordreset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    });

    Route::middleware(['auth'])->group(function () {


        /*****************
         *  Event management
         *   - defined in this route as not all users will have admin access
         ****************/
        Route::get('/events/manage', 'Events\Auth\EventController@getAllEvents');




        // Register for an event
        Route::get('/event/register/{eventurl}', 'Events\PublicEvents\EventController@getEventRegistration');

        // Logout
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');


        /*****************
         *  User profile
         ****************/

        // Profile
        Route::get('profile', 'Auth\ProfileController@getDashboard');
        Route::get('profile/mydetails', 'Auth\ProfileController@getMyDetails');
        Route::post('profile/mydetails', 'Auth\ProfileController@updateProfile')->name('updateprofile');

        // Show my events
        Route::get('/profile/myevents', 'Auth\ProfileController@getMyEvents');

        // Show my results
        Route::get('/profile/myresults', 'Auth\ProfileController@getMyResults');



    });


    Route::middleware(['admin'])->group(function () {


    });


    Route::middleware(['superadmin'])->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        // Clubs
        Route::get('admin/clubs', 'Admin\ClubController@get');
        Route::get('admin/clubs/create', 'Admin\ClubController@getCreateView');
        Route::post('admin/clubs/create', 'Admin\ClubController@createClub');

        // Divisions
        Route::get('admin/divisions', 'Admin\DivisionController@get');
        Route::get('admin/divisions/create', 'Admin\DivisionController@getCreateView');
        Route::post('admin/divisions/create', 'Admin\DivisionController@createDivision');

        // Organisations
        Route::get('admin/organisations', 'Admin\OrganisationController@get');
        Route::get('admin/organisations/create', 'Admin\OrganisationController@getCreateView');
        Route::post('admin/organisation/create', 'Admin\OrganisationController@createOrganisation');

        // Rounds
        Route::get('admin/rounds', 'Admin\RoundController@get');
        Route::get('admin/rounds/create', 'Admin\RoundController@getCreateView');
        Route::post('admin/rounds/create', 'Admin\RoundController@createRound');

        // Competitions
        Route::get('admin/competitions', 'Admin\CompetitionController@get');
        Route::get('admin/competitions/create', 'Admin\CompetitionController@getCreateView');
        Route::post('admin/competitions/create', 'Admin\CompetitionController@createCompetition');

        // Users
        Route::get('admin/users', 'Admin\UsersController@get');
    });




});
