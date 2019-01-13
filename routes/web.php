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
use App\Events\BalanceChanged;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/fire', function () {
    event(new BalanceChanged);
    return 'fired';
});

Route::get('/results/status', 'RacesController@checkResultsPosting');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'DashboardController@index')->name('user.dashboard');

Route::post('/userlogout', 'UsersController@logout')->name('user.logout');


Route::get('/races', 'RacesController@index')->name('races.index');
Route::get('race/{id}', 'RacesController@singleRace');

Route::get('/deposit', 'PaymentsController@deposit')->name('deposit.index');
Route::post('/deposit', 'PaymentsController@makeDeposit')->name('payment.deposit');

Route::prefix('admin')->group(function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::post('/race/{race}', 'AdminController@updateStatus')->name('admin.race.status.update');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});