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

Route::view('/', 'welcome');
Auth::routes();

Route::prefix('admin')->group(function () {
    Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@adminLogin');
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::prefix('store')->group(function () {
    Route::get('/home', 'Hos\HomeController@index')->name('hos.home')->middleware('auth');
    Route::get('/order', 'Hos\HomeController@storeOrder')->name('hos.store.order')->middleware('auth');
    Route::get('/profile', 'Hos\HomeController@profile')->name('hos.profile')->middleware('auth');
    Route::post('/add_order', 'Hos\HomeController@addOrder')->name('hos.add.order')->middleware('auth');
    Route::get('/order_detail/{order_code}', 'Hos\HomeController@orderDetail')->name('hos.order.detail')->middleware('auth');
    Route::post('/order_detail', 'Hos\HomeController@orderUpdate')->name('hos.order.update')->middleware('auth');
    Route::post('search_data', 'Hos\HomeController@searchData')->name('hos.search.data');
    Route::post('material_data', 'Hos\HomeController@materialData')->name('hos.material.data');
});

Route::prefix('inbound')->group(function () {
    Route::get('/home', 'Inbound\HomeController@index')->name('inbound.home')->middleware('auth');
    Route::get('/request_order_detail/{order_code}', 'Inbound\HomeController@requestOrderDetail')->name('inbound.order.detail')->middleware('auth');
    Route::post('/order_detail', 'Inbound\HomeController@orderUpdate')->name('inbound.order.update')->middleware('auth');
    Route::post('/order_reject', 'Inbound\HomeController@orderRejected')->name('inbound.order.reject')->middleware('auth');
});

// Route::group(['middleware' => ['auth', 'approve']],function() {
//     Route::prefix('3pl')->group(function () {
//         Route::get('/home', 'Approve\HomeController@index')->name('approve.home');
//     });
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
