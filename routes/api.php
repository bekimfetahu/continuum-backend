<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

//Route::get('/transactions/index', function () {
//    return 'Hello World';
//});

//Route::get('/transactions/index', 'TransactionController@index');
//Route::resource('transactions', 'TransactionController')->except(['show', 'edit','create']);

Route::post('/login', 'AuthController@login')->name('login');

// Authenticated User routes
Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', 'AuthController@user');
    Route::post('/logout', 'AuthController@logout');

    /**
     * Client CRUD API
     */
    Route::get('clients/{client}', 'ClientController@client'); // fetch single client
    Route::resource('clients', 'ClientController')->except(['show', 'edit','create']);

    /**
     * Transaction API
     */
    Route::resource('transactions', 'TransactionController')->except(['show', 'edit','create']);
});
