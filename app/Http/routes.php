<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('pages.home');
});

// Terms routes...
get('terms', 'TermsController@index');
get('terms/create', 'TermsController@create');
get('terms/{terms}', 'TermsController@show');
get('terms/{slugUnique}/edit', [
    'as' => 'terms.edit', 'uses' => 'TermsController@edit'
    ]);
patch('terms/{slugUnique}', [
    'as' => 'terms.update', 'uses' => 'TermsController@update'
    ]);
patch('terms/{slugUnique}/status', [
    'as' => 'terms.updateStatus', 'uses' => 'TermsController@updateStatus'
    ]);
post('terms', 'TermsController@store');


// Sugesstions
get('suggestions', 'TermsController@suggestions');

// Pages...
// TODO: implement /home route for users.
get('home', function () {
   return view('pages.home');
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Areas and fields
Route::resource('scientific-areas', 'ScientificAreasController');
Route::resource('scientific-areas.scientific-fields', 'ScientificFieldsController');

Route::resource('definitions', 'DefinitionsController');

Route::resource('languages', 'LanguagesController');