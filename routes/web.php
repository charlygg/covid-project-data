<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/index/{date?}', 'CovidController@index');

Route::get('/buscarInfo', 'CovidController@buscarInfo');


// Route::get("/manual", "CovidController@manualInfo");
/*
Route::get('/about', function ($id) {
    return view("about");
});

Route::get('/today', function () {

    $csvCovid = file_get_contents('https://api.covidtracking.com/v1/us/daily.json');
    //read csv headers
    return view('today', array('csv' => $csvCovid));
});

*/

Route::get('/home', 'HomeController@index')->name('home');
