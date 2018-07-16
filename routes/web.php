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
        Route::get('/resetpassword', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');


    });

    Route::middleware(['auth'])->group(function () {
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        //Route::get('/admin/test/createsiteroles', 'Admin\TestController@createsiteroles');


    });




});
