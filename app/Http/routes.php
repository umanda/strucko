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

// Pages...
Route::get('/', 'PagesController@getHome');
get('home', function () {
   return view('pages.user');
});
get('contact', function () {
   return view('pages.contact');
});

// Terms routes.
get('terms', 'TermsController@index');
get('terms/create', 'TermsController@create');
get('terms/{terms}', 'TermsController@show');
get('terms/{slugUnique}/edit', [
    'as' => 'terms.edit', 'uses' => 'TermsController@edit'
    ]);
post('terms/{slugUnique}/translations', 'ConceptsController@addTranslation');
post('terms/{slugUnique}/synonyms', 'ConceptsController@addSynonym');
post('terms/{slugUnique}/vote/up', 'TermVotesController@voteUp');
post('terms/{slugUnique}/vote/down', 'TermVotesController@voteDown');
post('terms/{slugUnique}/status/approve', 'TermsController@approveTerm');
post('terms/{slugUnique}/status/reject', 'TermsController@rejectTerm');
patch('terms/{slugUnique}', [
    'as' => 'terms.update', 'uses' => 'TermsController@update'
    ]);
patch('terms/{slugUnique}/status', [
    'as' => 'terms.updateStatus', 'uses' => 'TermsController@updateStatus'
    ]);
post('terms', 'TermsController@store');

// Definition routes.
post('definitions', 'DefinitionsController@store');
post('definitions/{id}/vote/up', 'DefinitionVotesController@voteUp');
post('definitions/{id}/vote/down', 'DefinitionVotesController@voteDown');
post('definitions/{id}/status/approve', 'DefinitionsController@approve');
post('definitions/{id}/status/reject', 'DefinitionsController@reject');

// Sugesstions
get('suggestions', 'SuggestionsController@index');
get('suggestions/terms', 'SuggestionsController@terms');
get('suggestions/merges', 'SuggestionsController@merges');
get('suggestions/merges/{id}', 'MergeSuggestionsController@show');
post('suggestions/merges/{id}/vote/up', 'MergeSuggestionsController@voteUp');
post('suggestions/merges/{id}/vote/down', 'MergeSuggestionsController@voteDown');
post('suggestions/merges/{id}/approveMerge', 'MergeSuggestionsController@voteDown');

get('suggestions/definitions', 'SuggestionsController@definitions');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/confirm/{token}', 'Auth\AuthController@getConfirm');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Areas and fields
Route::resource('scientific-areas', 'ScientificAreasController');
Route::resource('scientific-areas.scientific-fields', 'ScientificFieldsController');



Route::resource('languages', 'LanguagesController');