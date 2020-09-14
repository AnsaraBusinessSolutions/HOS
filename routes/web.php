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

Route::group(['middleware' => ['auth','hosmid']],function() {
    Route::prefix('store')->group(function () {
        Route::get('/home', 'Hos\HomeController@index')->name('hos.home');
        Route::get('/profile', 'Hos\HomeController@profile')->name('hos.profile')->middleware('auth');
        Route::post('/add_order', 'Hos\HomeController@addOrder')->name('hos.add.order')->middleware('auth');
        Route::post('/search_data', 'Hos\HomeController@searchData')->name('hos.search.data')->middleware('auth');
        Route::post('/material_data', 'Hos\HomeController@materialData')->name('hos.material.data')->middleware('auth');
    });
});

Route::group(['middleware' => ['auth', 'approve']],function() {
    Route::prefix('3pl')->group(function () {
        Route::get('/home', 'Approve\HomeController@index')->name('approve.home');
    });
});



// Route::prefix('aproove')->group(function () {
//     Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
//     Route::post('/login', 'Auth\LoginController@login');
//     Route::get('/home', 'Hos\HomeController@index')->name('hos.home')->middleware('auth');
// });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
