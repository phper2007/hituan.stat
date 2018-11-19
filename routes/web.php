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

Route::get('/', 'PagesController@root')->name('root');
Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    //收货地址
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');

    Route::match(['get', 'post'] ,'user_addresses/standard', 'UserAddressesController@standard')->name('user_addresses.standard');

    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

    //产品信息
    Route::get('products', 'ProductsController@index')->name('products.index');
    Route::get('products/import_offer', 'ProductsController@importOffer')->name('products.import_offer');
    Route::post('products/import_offer_analysis', 'ProductsController@importOfferAnalysis')->name('products.import_offer_analysis');

    Route::get('products/import_solitaire', 'ProductsController@importSolitaire')->name('products.import_solitaire');
    Route::post('products/import_solitaire_analysis', 'ProductsController@importSolitaireAnalysis')->name('products.import_solitaire_analysis');
    Route::post('products/store_import', 'ProductsController@storeImport')->name('products.store_import');
    Route::get('products/create', 'ProductsController@create')->name('products.create');
    Route::post('products', 'ProductsController@store')->name('products.store');
    Route::get('products/{product}', 'ProductsController@edit')->name('products.edit');
    Route::put('products/{product}', 'ProductsController@update')->name('products.update');
    Route::delete('products/{product}', 'ProductsController@destroy')->name('products.destroy');

    //订单信息
    Route::get('orders', 'OrdersController@index')->name('orders.index');

    Route::get('orders/check/{order}', 'OrdersController@check')->name('orders.check');
    Route::get('orders/bill', 'OrdersController@bill')->name('orders.bill');

    Route::get('orders/{user_address}', 'OrdersController@create')->name('orders.create');
    Route::post('orders', 'OrdersController@store')->name('orders.store');

    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

    //快递
    Route::get('expresses/search/{user_address}', 'ExpressesController@search')->name('expresses.search');
    Route::get('expresses/analysis', 'ExpressesController@analysis')->name('expresses.analysis');
});