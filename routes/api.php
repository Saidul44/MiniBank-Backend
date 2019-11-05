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

Route::group(['prefix' => 'v1'], function(){

    //authentication login and registration
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    // Route::get('logout', 'Auth\LoginController@logout');


    Route::group(['middleware' => 'auth:api'], function() {

        Route::post('deposit', 'DepositController@store');

        Route::post('fund-transfer', 'FundTransferController@store');

        Route::get('customer-balance/{id}', 'CustomerController@balance');

        Route::get('customer-info/{id}', 'CustomerController@customerInfo');

        Route::get('customer-info', 'CustomerController@index');

        Route::get('statement', 'StatementController@statement');

        Route::post('new-account-request', 'CustomerController@newAccountRequest');

        Route::get('logout', 'Auth\LoginController@logout');
    });


});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
