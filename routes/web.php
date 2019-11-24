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

Route::group(['prefix' => 'salesforce'], function () {
    Route::get('connect', 'SalesforceContoller@connect');
    Route::get('callback', 'SalesforceContoller@callback');

    Route::group(['prefix' => 'products'], function() {
        Route::get('create', 'SalesforceContoller@createProduct');
        Route::get('/{id?}', 'SalesforceContoller@getProducts');
    });

    Route::group(['prefix' => 'pets'], function() {
        Route::get('create', 'SalesforceContoller@createPet');
        Route::get('/{id?}', 'SalesforceContoller@getPets');
    });

    Route::group(['prefix' => 'orders'], function() {
        Route::get('/', 'SalesforceContoller@getOrders');
    });

});
