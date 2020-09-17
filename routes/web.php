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



Route::prefix('store')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('hos.login');
    Route::post('/login', 'Auth\LoginController@login')->name('hos.login');
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
    Route::get('/login', 'Auth\LoginController@showInboundLoginForm')->name('inbound.login');
    Route::post('/login', 'Auth\LoginController@inboundLogin')->name('inbound.login');

    Route::get('/home', 'Inbound\HomeController@index')->name('inbound.home');
    Route::get('/request_order_detail/{order_code}', 'Inbound\HomeController@requestOrderDetail')->name('inbound.order.detail');
    Route::post('/order_detail', 'Inbound\HomeController@orderUpdate')->name('inbound.order.update');
    Route::post('/order_reject', 'Inbound\HomeController@orderRejected')->name('inbound.order.reject');
    Route::post('/order_approve', 'Inbound\HomeController@orderApprove')->name('inbound.order.approve');
});

Route::prefix('hos3pl')->group(function () {
    Route::get('/login', 'Auth\LoginController@showHos3plLoginForm')->name('hos3pl.login');
    Route::post('/login', 'Auth\LoginController@hos3plLogin')->name('hos3pl.login');

    Route::get('/home', 'Hos_3pl\HomeController@index')->name('hos3pl.home');
    Route::get('/order_detail/{order_code}', 'Hos_3pl\HomeController@requestOrderDetail')->name('hos3pl.order.detail');
    Route::post('/order_status', 'Hos_3pl\HomeController@orderStatusUpdate')->name('hos3pl.order.status.update');
});

// Route::group(['middleware' => ['auth', 'approve']],function() {
//     Route::prefix('3pl')->group(function () {
//         Route::get('/home', 'Approve\HomeController@index')->name('approve.home');
//     });
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
