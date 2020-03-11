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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $customer = new \App\Customer();
    return $request->user();
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth\Http\Controllers'], function(){
    Route::post('login','AuthController@login')->name('auth.login');
    Route::post('logout','AuthController@logout')->name('auth.logout');
    Route::get('me','AuthController@me')->name('auth.me');
});

Route::group(['prefix' => 'customers','customers','namespace' => 'Customer\Http\Controllers'], function () {
    Route::post('', 'CustomerController@store')->name('customers.store');
    Route::put('{customer}','CustomerController@update')->name('customers.update');
    Route::delete('', 'CustomerController@destroy')->name('customers.destroy');
    Route::get('', 'CustomerController@index')->name('customers.list');
    Route::get('all', 'CustomerController@all')->name('customers.all');
    Route::get('{id}', 'CustomerController@show')->name('customers.show');

});
