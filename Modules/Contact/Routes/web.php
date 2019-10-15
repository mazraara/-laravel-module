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

//Route::prefix('contact')->group(function() {
//    Route::get('/', 'ContactController@index');
//});

// frontend routes

Route::get('contact', [
    'as' => 'public.contact.show',
    'uses' => 'Frontend\ContactRequestController@show',
]);

Route::post('contact', [
    'as' => 'public.contact.submit',
    'uses' => 'Frontend\ContactRequestController@store',
]);

// backend routes

//todo: move to separate directories Controller
