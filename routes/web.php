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

Route::view('/', 'welcome')->name('main_page');
Auth::routes();


Route::prefix('admin')->group(function () {
    Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@adminLogin')->name('admin.login');
    Route::post('/logout', 'Auth\LoginController@adminLogout')->name('admin.logout');
    Route::get('/home', 'Admin\HomeController@index')->name('admin.home');
});

Route::prefix('store')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('hos.login');
    Route::post('/login', 'Auth\LoginController@login')->name('hos.login');
    Route::get('/home', 'Hos\HomeController@index')->name('hos.home')->middleware('auth');
    Route::get('/order', 'Hos\HomeController@storeOrder')->name('hos.store.order')->middleware('auth');
    Route::post('/batch_data', 'Hos\HomeController@batchData')->name('hos.order.batch.data')->middleware('auth');
    Route::get('/profile', 'Hos\HomeController@profile')->name('hos.profile')->middleware('auth');
    Route::post('/add_order', 'Hos\HomeController@addOrder')->name('hos.add.order')->middleware('auth');
    Route::get('/order_detail/{order_id}', 'Hos\HomeController@orderDetail')->name('hos.order.detail')->middleware('auth');
    Route::post('/order_detail', 'Hos\HomeController@orderUpdate')->name('hos.order.update')->middleware('auth');
    Route::post('search_data', 'Hos\HomeController@searchData')->name('hos.search.data');
    Route::post('material_data', 'Hos\HomeController@materialData')->name('hos.material.data');
});

Route::prefix('custodian')->group(function () {
    Route::get('/login', 'Auth\LoginController@showCustodianLoginForm')->name('custodian.login');
    Route::post('/login', 'Auth\LoginController@custodianLogin')->name('custodian.login');
    Route::post('/logout', 'Auth\LoginController@custodianLogout')->name('custodian.logout');

    Route::get('/home', 'Custodian\HomeController@index')->name('custodian.home');
    Route::get('/request_order_detail/{order_id}', 'Custodian\HomeController@requestOrderDetail')->name('custodian.order.detail');
    Route::post('/order_detail', 'Custodian\HomeController@orderUpdate')->name('custodian.order.update');
    Route::post('/batch_data', 'Custodian\HomeController@batchData')->name('custodian.order.batch.data');
    Route::post('/order_reject', 'Custodian\HomeController@orderRejected')->name('custodian.order.reject');
    Route::post('/order_approve', 'Custodian\HomeController@orderApprove')->name('custodian.order.approve');
});

Route::prefix('hos3pl')->group(function () {
    Route::get('/login', 'Auth\LoginController@showHos3plLoginForm')->name('hos3pl.login');
    Route::post('/login', 'Auth\LoginController@hos3plLogin')->name('hos3pl.login');
    Route::post('/logout', 'Auth\LoginController@hos3plLogout')->name('hos3pl.logout');

    Route::get('/home', 'Hos_3pl\HomeController@index')->name('hos3pl.home');
    Route::get('/order_detail/{order_id}', 'Hos_3pl\HomeController@requestOrderDetail')->name('hos3pl.order.detail');
   // Route::post('/order_status', 'Hos_3pl\HomeController@orderStatusUpdate')->name('hos3pl.order.status.update');
    Route::post('/order_status', 'Hos_3pl\HomeController@orderDispatch')->name('hos3pl.order.dispatch');
    Route::post('/order_batch_insert', 'Hos_3pl\HomeController@orderBatchInsert')->name('hos3pl.order.batch.insert');
    Route::post('/batch_data', 'Hos_3pl\HomeController@batchData')->name('hos3pl.batch.data');
});

// Route::group(['middleware' => ['auth', 'approve']],function() {
//     Route::prefix('3pl')->group(function () {
//         Route::get('/home', 'Approve\HomeController@index')->name('approve.home');
//     });
// });

Auth::routes();

