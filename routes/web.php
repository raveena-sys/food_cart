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
/*Route::group(['middleware' => 'guest'], function () {

    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::post('signup', ['as' => 'signup', 'uses' => 'AuthController@signup']);
    Route::get('managers/{id}', 'AuthController@getManagers');
    Route::get('get-company/{type}', 'AuthController@getCompany');
    Route::get('verify-account/{token}', 'AuthController@verifyAccount');
    Route::get('job-list', 'HomeController@jobList');

    Route::get('security', 'HomeController@security');

});*/
/***************Web routes newly started************/

Route::get('/', ['as' => 'home', 'uses' => 'Front\HomeController@index']);
Route::get('store_list', 'Front\HomeController@getstores');
Route::get('order_type/{id}', 'Front\HomeController@order_type');
Route::get('{order_type}/menu/{price}/{head?}', 'Front\HomeController@mainMenu');
Route::get('menu/{id}', 'Front\StoreController@getStoreMasterList');
Route::get('product/{id}', 'Front\StoreController@productDetails');
Route::post('product', 'Front\StoreController@productCustomiseDetails');
Route::get('checkout', 'Front\OrderController@checkout');
Route::get('checkout/user', 'Front\OrderController@checkout_user');
Route::post('validation/checkzipcode', 'Front\OrderController@checkzipcode');

Route::match(array('GET', 'POST'), 'save_user_detail', 'Front\OrderController@save_user_detail' );
Route::post('save_order', 'Front\OrderController@save_order');

Route::get('contact_us', 'Front\ContactUsController@contactus');
Route::get('get_customise/{id}', 'Front\StoreController@get_customise');

Route::post('add_contactus', 'Front\ContactUsController@addContactUs');



Route::get('topping_master', 'DashboardController@topping');
Route::post('insert/getData', 'DashboardController@getData');

Route::get('about_us', 'Front\HomeController@aboutUs');
Route::get('privacy_policy', 'Front\HomeController@privacyPolicy');

Route::get('increament_decrement', 'Front\StoreController@increamentCartQty');
Route::get('pdfDownload', 'Front\OrderController@pdfDownload');
/***************Web routes newly started************/



