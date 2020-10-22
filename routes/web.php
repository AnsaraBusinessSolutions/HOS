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
Route::get('locale/{locale}', function ($locale){
    \Session::put('locale', $locale);
    return redirect()->back();
});

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
    Route::get('/stock_report', 'Hos\HomeController@stockReport')->name('hos.stock.report');
    Route::post('/search_stock', 'Hos\HomeController@searchStock')->name('hos.search.stock');

    //Department related route
    Route::get('/department_order', 'Hos\DepartmentController@departmentOrder')->name('hos.department.order')->middleware('auth');
    Route::post('department_search_data', 'Hos\DepartmentController@departmentSearchData')->name('hos.department.search.data');
    Route::post('department_material_data', 'Hos\DepartmentController@departmentMaterialData')->name('hos.department.material.data');
    Route::post('/add_department_order', 'Hos\DepartmentController@addDepartmentOrder')->name('hos.add.department.order')->middleware('auth');
    Route::get('/department_order_list', 'Hos\DepartmentController@departmentOrderList')->name('hos.department.order.list')->middleware('auth');
    Route::get('/department_order_detail/{department_order_id}', 'Hos\DepartmentController@departmentOrderDetail')->name('hos.department.order.detail')->middleware('auth');
    Route::post('/add_stock_consumption', 'Hos\DepartmentController@addStockConsumption')->name('hos.add.stock.consumption')->middleware('auth');
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
    Route::post('/order_date_change', 'Custodian\HomeController@orderDateChange')->name('custodian.order.date.change');
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
    Route::post('/display_batch_data', 'Hos_3pl\HomeController@displayBatchData')->name('hos3pl.display.batch.data');
    Route::get('/open_order', 'Hos_3pl\HomeController@openOrder')->name('hos3pl.open.order');
    Route::get('/open_order_detail/{order_id}', 'Hos_3pl\HomeController@openOrderDetail')->name('hos3pl.open.order.detail');
    Route::get('/display_order', 'Hos_3pl\HomeController@displayOrder')->name('hos3pl.display.order');
    Route::get('/display_order_detail/{order_id}', 'Hos_3pl\HomeController@displayOrderDetail')->name('hos3pl.display.order.detail');
    Route::post('tradecode_data', 'Hos_3pl\HomeController@tradeCodeData')->name('hos3pl.tradecode.data');
});

Route::prefix('inventory')->group(function () {
    Route::get('/login', 'Auth\LoginController@showInventoryLoginForm')->name('inventory.login');
    Route::post('/login', 'Auth\LoginController@inventoryLogin')->name('inventory.login');
    Route::post('/logout', 'Auth\LoginController@inventoryLogout')->name('inventory.logout');
    Route::get('/home', 'Inventory\HomeController@index')->name('inventory.home');
    Route::get('/order_detail/{order_id}', 'Inventory\HomeController@orderDetail')->name('inventory.order.detail');
    Route::post('/create_grn', 'Inventory\HomeController@createGrn')->name('inventory.create.grn');
    Route::get('/open_order', 'Inventory\HomeController@openOrder')->name('inventory.open.order');
    Route::get('/open_order_detail/{order_id}', 'Inventory\HomeController@openOrderDetail')->name('inventory.open.order.detail');
    Route::get('/display_order', 'Inventory\HomeController@displayOrder')->name('inventory.display.order');
    Route::get('/display_order_detail/{order_id}', 'Inventory\HomeController@displayOrderDetail')->name('inventory.display.order.detail');
});

Auth::routes();

