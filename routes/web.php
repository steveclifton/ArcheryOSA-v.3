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

//Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::get('/events', 'Events\PublicEvents\EventController@getAllEvents');

Route::get('/events/results', 'Events\PublicEvents\EventController@getPreviousEventsList')->name('results');
// Get specific event results
Route::get('/event/results/{eventurl}', 'Events\PublicEvents\EventResultsController@getEventResultsList');

Route::get('/event/results/{eventurl}/{eventcompetitionid}', 'Events\PublicEvents\EventResultsController@getCompetitionResults');


// Define create in public route to show users they can apply to create events
Route::get('/events/create', 'Events\Auth\EventController@getCreateEventView');

// Get specific event details
Route::get('/event/details/{eventurl}', 'Events\PublicEvents\EventController@getEventDetails')->name('event');



// Results and Ranking
Route::get('/rankings/nz', 'Ranking\RankingController@getCountryRankings');
Route::get('/records/nz', 'Record\RecordController@getCountryRecords');


// Get users public profile
Route::get('/users/{username}', 'Auth\PublicProfile\UserController@getPublicProfile');

Route::get('profile/relationship/authorise/{hash}', 'Auth\ProfileController@authoriseRelation');



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
        Route::get('/events/manage/{eventurl}', 'Events\Auth\EventController@getEventManageView');
        Route::get('/events/manage/update/{eventurl}', 'Events\Auth\EventController@getUpdateEventView');
        Route::post('/events/manage/create', 'Events\Auth\EventController@createEvent');
        Route::post('/events/manage/update/{eventurl}', 'Events\Auth\EventController@updateEvent');

        // event competitions
        Route::get('events/manage/competitions/{eventurl}', 'Events\Auth\EventCompetitionController@getEventCompetitionsView');
        Route::post('events/manage/competitions/create/{eventurl}', 'Events\Auth\EventCompetitionController@createEventCompetition');
        Route::post('events/manage/competitions/update/{eventurl}', 'Events\Auth\EventCompetitionController@updateEventCompetition');

        // Exports
        Route::get('events/manage/exports/{eventurl}', 'Export\EventExportController@getExportView');

        // League event competitions
        Route::post('events/manage/competitions/league/create/{eventurl}', 'Events\Auth\EventCompetitionController@createLeagueCompetition');
        Route::post('events/manage/competitions/league/update/{eventurl}', 'Events\Auth\EventCompetitionController@updateLeagueCompetition');


        Route::get('events/manage/communication/{eventurl}', 'Events\Auth\EventCommunicationController@getEventCommView');
        Route::post('events/manage/communication/{eventurl}', 'Events\Auth\EventCommunicationController@sendEventEmail');

        // event settings
        Route::get('events/manage/settings/{eventurl}', 'Events\Auth\EventSettingsController@getEventSettingsView');
        Route::post('events/manage/settings/{eventurl}', 'Events\Auth\EventSettingsController@updateEventSettings');

        // event entries
        Route::get('events/manage/evententries/{eventurl}', 'Events\Auth\EventEntryController@getEventEntriesView');
        Route::get('events/manage/evententries/{eventurl}/add', 'Events\Auth\EventEntryController@getEventEntryAddView');
        Route::get('events/manage/evententries/{eventurl}/update/{username}', 'Events\Auth\EventEntryController@getEventEntryUpdateView');
        Route::get('events/manage/evententries/{eventurl}/email/{username}', 'Events\Auth\EventEntryController@getEventEntryEmailView');
        Route::post('event/registration/create/admin/{eventurl}', 'Events\Auth\EventRegistrationController@createAdminRegistration');
        Route::post('event/registration/update/admin/{eventurl}', 'Events\Auth\EventRegistrationController@updateAdminRegistration');
        Route::post('event/registration/email/admin/{eventurl}', 'Events\Auth\EventEntryController@sendEventEntryEmail');

        // USERS STUFF

        // Register for an event
        Route::get('event/register/{eventurl}', 'Events\Auth\EventRegistrationController@getRegistrationList');
        Route::get('event/registration/{eventurl}/{username}', 'Events\Auth\EventRegistrationController@getRegistration');
        Route::post('event/registration/create/{eventurl}', 'Events\Auth\EventRegistrationController@createRegistration');
        Route::post('event/registration/update/{eventurl}', 'Events\Auth\EventRegistrationController@updateRegistration');

        // event admins
        Route::get('events/manage/eventadmins/{eventurl}', 'Events\Auth\EventAdminController@getEventAdminView');

        Route::get('events/manage/eventadmins/clubs/{eventurl}/{eventadminid}', 'Events\Auth\EventAdminController@getEventAdminClubView');
        Route::post('events/manage/eventadmins/clubs/add/{eventurl}', 'Events\Auth\EventAdminController@addClubsToUser');

        Route::get('events/manage/eventadmins/schools/{eventurl}/{eventadminid}', 'Events\Auth\EventAdminController@getEventAdminSchoolView');
        Route::post('events/manage/eventadmins/schools/add/{eventurl}', 'Events\Auth\EventAdminController@addSchoolsToUser');



        // Logout
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');


        // User scoring - Only Leagues are supported now
        Route::get('scoring', 'Events\Auth\EventController@getUserEventScoringList');
        Route::get('scoring/{eventurl}', 'Events\Auth\EventController@getUserEventScoringView');
        Route::post('events/scoring/league/{eventurl}', 'Events\Scoring\ScoringController@postLeagueScore');


        // Event Scoring
        Route::get('/event/scoring/{eventurl}', 'Events\Scoring\ScoringController@getEventScoringList');

        Route::get('/event/manage/scoring/{eventurl}/{eventcompetitionid}', 'Events\Scoring\ScoringController@getEventScoringView');
        Route::post('/events/scoring/{eventurl}', 'Events\Scoring\ScoringController@postScores');




        /*****************
         *  User profile
         ****************/

        // Profile
        Route::get('profile', 'Auth\ProfileController@getDashboard');
        Route::get('profile/mydetails', 'Auth\ProfileController@getMyDetails');
        Route::post('profile/mydetails', 'Auth\ProfileController@updateProfile')->name('updateprofile');

        // Relationships
        Route::get('profile/relationships', 'Auth\ProfileController@getRelationshipsList');
        Route::get('profile/relationships/request', 'Auth\ProfileController@getRelationshipsRequest');
        Route::post('profile/relationships/request', 'Auth\ProfileController@requestRelationship');
        Route::post('profile/relationships/remove', 'Auth\ProfileController@removeRelationship');

        //Children
        Route::get('profile/children', 'Auth\ProfileController@getChildrenList');
        Route::get('profile/children/create', 'Auth\ProfileController@getChildCreate');
        Route::get('profile/children/update/{username}', 'Auth\ProfileController@getChild');

        Route::post('profile/children/create', 'Auth\ProfileController@createChild');
        Route::post('profile/children/update', 'Auth\ProfileController@updateChild');



        // Show my events
        Route::get('profile/myevents', 'Auth\ProfileController@getMyEvents');

        // Show my results
        Route::get('profile/myresults', 'Auth\ProfileController@getMyResults');



        /**
         * AJAX
         */
        Route::post('ajax/evententries/search', 'Events\Auth\AjaxController@getUser');

        Route::post('ajax/events/manage/competition', 'Events\Auth\AjaxController@getMarkup');
        Route::post('ajax/events/manage/{eventurl}/approveentry', 'Events\Auth\EventEntryController@approveEntry');
        Route::post('ajax/events/manage/{eventurl}/approvepaid', 'Events\Auth\EventEntryController@approvePaid');
        Route::post('ajax/events/manage/{eventurl}/sendconfirmation', 'Events\Auth\EventEntryController@sendApprove');
        Route::post('ajax/events/manage/{eventurl}/removeentry', 'Events\Auth\EventEntryController@removeEntry');
        Route::post('ajax/events/manage/{eventurl}/processleague', 'League\LeagueController@processLeagueResults');

        Route::post('ajax/events/manage/{eventurl}/updateadmin', 'Events\Auth\EventAdminController@updateUser');
        Route::post('ajax/events/manage/{eventurl}/deleteadmin', 'Events\Auth\EventAdminController@deleteUser');
        Route::post('ajax/events/manage/{eventurl}/addadmin', 'Events\Auth\EventAdminController@addUser');

        /**
         * EXPORT
         */

        Route::get('event/export/entries/{eventurl}/{type}', 'Export\EventExportController@exportevententries');

        Route::get('event/export/results/{eventurl}/{eventcompetitionid}', 'Export\EventExportController@exportEventScores');


    });


    Route::middleware(['admin'])->group(function () {


    });


    Route::middleware(['superadmin'])->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        Route::get('phpinfo', 'HomeController@debug');

        // Clubs
        Route::get('admin/clubs', 'Admin\ClubController@get');
        Route::get('admin/clubs/create', 'Admin\ClubController@getCreateView');
        Route::post('admin/clubs/create', 'Admin\ClubController@createClub');
        Route::get('admin/clubs/update/{clubid}', 'Admin\ClubController@getUpdateView');
        Route::post('admin/clubs/update/{clubid}', 'Admin\ClubController@updateClub');

        // Schools
        Route::get('admin/schools', 'Admin\SchoolController@get');
        Route::get('admin/schools/create', 'Admin\SchoolController@getCreateView');
        Route::post('admin/schools/create', 'Admin\SchoolController@createSchool');
        Route::get('admin/schools/update/{schoolid}', 'Admin\SchoolController@getUpdateView');
        Route::post('admin/schools/update/{schoolid}', 'Admin\SchoolController@updateSchool');

        // Divisions
        Route::get('admin/divisions', 'Admin\DivisionController@get');
        Route::get('admin/divisions/create', 'Admin\DivisionController@getCreateView');
        Route::post('admin/divisions/create', 'Admin\DivisionController@createDivision');
        Route::get('admin/divisions/update/{divisionid}', 'Admin\DivisionController@getUpdateView');
        Route::post('admin/divisions/update/{divisionid}', 'Admin\DivisionController@updateDivision');

        // Organisations
        Route::get('admin/organisations', 'Admin\OrganisationController@get');
        Route::get('admin/organisations/create', 'Admin\OrganisationController@getCreateView');
        Route::post('admin/organisation/create', 'Admin\OrganisationController@createOrganisation');
        Route::get('admin/organisations/update/{organisationid}', 'Admin\OrganisationController@getUpdateView');
        Route::post('admin/organisation/update/{organisationid}', 'Admin\OrganisationController@updateOrganisation');

        // Rounds
        Route::get('admin/rounds', 'Admin\RoundController@get');
        Route::get('admin/rounds/create', 'Admin\RoundController@getCreateView');
        Route::post('admin/rounds/create', 'Admin\RoundController@createRound');
        Route::get('admin/rounds/update/{roundid}', 'Admin\RoundController@getUpdateView');
        Route::post('admin/rounds/update/{roundid}', 'Admin\RoundController@updateRound');

        // Competitions
        Route::get('admin/competitions', 'Admin\CompetitionController@get');
        Route::get('admin/competitions/create', 'Admin\CompetitionController@getCreateView');
        Route::post('admin/competitions/create', 'Admin\CompetitionController@createCompetition');

        Route::get('admin/competitions/update/{competitionid}', 'Admin\CompetitionController@getUpdateView');
        Route::post('admin/competitions/update/{competitionid}', 'Admin\CompetitionController@updateCompetition');

        // Users
        Route::get('admin/users', 'Admin\UsersController@get');
    });


});
