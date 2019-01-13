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


Route::post('race/adduser', 'RacesController@addUser');
Route::get('race/checkuser', 'RacesController@checkUser');
Route::get('race/usercount', 'RacesController@updateUserCount');
Route::post('race/status/{race}', 'RacesController@updateStatus');

Route::post('race/setselection', 'RacesController@setSelection');

Route::post('/newselection', 'SelectionsController@create');
Route::post('/updateselection', 'SelectionsController@update');
Route::get('/points/{user}', 'SelectionsController@userTotals');

Route::get('racers', 'RacesController@racersTest');

Route::get('/userdata', 'PaymentsController@userData');

Route::get('race', 'RacesController@singleRaceApi');
Route::get('races', 'RacesController@loadApi');
Route::get('nextrace', 'RacesController@nextRace');
Route::post('race/adduser', 'RacesController@addUser');
Route::get('race/checkuser', 'RacesController@checkUser');
Route::get('race/usercount', 'RacesController@updateUserCount');

Route::get('events', 'EventsController@loadApi');


// Route::get('lobby/{id}/add/{user}', 'LobbyController@addUser');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
