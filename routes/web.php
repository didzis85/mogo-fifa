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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/init', 'TournamentController@init');
Route::get('/tournament/{id}/process', ['as' => 'process' , 'uses' => 'TournamentController@process'] );
Route::get('/tournament/{id}/results', ['as' => 'results' , 'uses' => 'TournamentController@results'] );
Route::get('/history', 'TournamentController@history' );