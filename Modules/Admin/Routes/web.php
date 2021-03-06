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
/*NEW ROUTES*/
Route::group(['prefix' => 'admin', 'middleware' => 'AdminGuest'], function () {
    Route::get('/login', function () {
        return view('admin::auth.login');
    });
    Route::get('/', function () {
        return redirect('admin/login');
    });
    
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::post('forgot', ['as' => 'forgot', 'uses' => 'AuthController@forgotPassord']);
    Route::get('reset-password/{token}', 'AuthController@resetPassword');
    Route::post('reset-password', 'AuthController@reset');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('topping_master', 'AdminController@topping');
    Route::post('getData', 'AdminController@getData');

    Route::get('profile-setting', ['as' => 'profile-setting', 'uses' => 'AuthController@index']);
    Route::post('profile-update', ['as' => 'profile-update', 'uses' => 'AuthController@updateProfile']);
    Route::get('/change-password', function () {
        return view('admin::change-password.index');
    });

    Route::post('update-password', ['as' => 'update-password', 'uses' => 'AuthController@updatePassword']);
    Route::get('/dashboard', 'DashboardController@getDashboaordOrderReport');
    

    Route::get('getStateList', 'StoreMasterController@getStateList');
    Route::get('getCityList', 'StoreMasterController@getCityList');
    
    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    /*================ Start Manage CMS Routes ================*/
    Route::group(['prefix' => 'manage-cms'], function () {
        Route::get('/', ['as' => 'manage-cms', 'uses' => 'CmsController@index']);
        Route::get('load-cms-list', ['as' => 'load-cms-list', 'uses' => 'CmsController@loadCmsList']);
        Route::get('edit/{id}', ['as' => 'edit-cms', 'uses' => 'CmsController@getCmsPageDetail']);
        Route::post('update-cms-page', ['as' => 'edit-cms', 'uses' => 'CmsController@updateCmsPageDetail']);
    });
    /*================ End Manage CMS Routes ================*/


    /*  Food Cart API  */
    /*================ Start Manage CategoryController Routes ================*/
    Route::group(['prefix' => 'manage-category'], function () {
        Route::get('', 'CategoryController@index');
        Route::get('list', 'CategoryController@getCategoryList');
        Route::post('add', 'CategoryController@addCategory');
        Route::post('update', 'CategoryController@updateCategory');
        Route::get('detail/{id}', 'CategoryController@getCategoryDetails');
        Route::get('view/{id}', 'CategoryController@viewCategory');
        Route::post('change-status', 'CategoryController@changeStatus');
        Route::delete('delete/{id}', 'CategoryController@deleteCategory');
    });


    /*================ Start Manage Sub CategoryController Routes ================*/
    Route::group(['prefix' => 'manage-sub-category'], function () {
        Route::get('', 'SubCategoryController@index');
        Route::get('list', 'SubCategoryController@getSubCategoryList');
        Route::post('add', 'SubCategoryController@addSubCategory');
        Route::post('update', 'SubCategoryController@updateSubCategory');
        Route::get('detail/{id}', 'SubCategoryController@getSubCategoryDetails');
        Route::get('view/{id}', 'SubCategoryController@viewSubCategory');
        Route::post('change-status', 'SubCategoryController@changeStatus');
        Route::delete('delete/{id}', 'SubCategoryController@deleteSubCategory');
    });

    /*================ Start Manage Pizza Crust Controller Routes ================*/
    Route::group(['prefix' => 'manage-pizza-crust'], function () {
        Route::get('', 'PizzaCrustController@index');
        Route::get('list', 'PizzaCrustController@getPizzaCrustList');
        Route::post('add', 'PizzaCrustController@addPizzaCrust');
        Route::post('update', 'PizzaCrustController@updatePizzaCrust');
        Route::get('detail/{id}', 'PizzaCrustController@getPizzaCrustDetails');
        Route::get('view/{id}', 'PizzaCrustController@viewPizzaCrust');
        Route::post('change-status', 'PizzaCrustController@changeStatus');
        Route::delete('delete/{id}', 'PizzaCrustController@deletePizzaCrust');
    });

    /*================ Start Manage Pizza Sauce Controller Routes ================*/
    Route::group(['prefix' => 'manage-pizza-sauce'], function () {
        Route::get('', 'PizzaSauceController@index');
        Route::get('list', 'PizzaSauceController@getPizzaSauceList');
        Route::post('add', 'PizzaSauceController@addPizzaSauce');
        Route::post('update', 'PizzaSauceController@updatePizzaSauce');
        Route::get('detail/{id}', 'PizzaSauceController@getPizzaSauceDetails');
        Route::get('view/{id}', 'PizzaSauceController@viewPizzaSauce');
        Route::post('change-status', 'PizzaSauceController@changeStatus');
        Route::delete('delete/{id}', 'PizzaSauceController@deletePizzaSauce');
    });



    /*================ Start Manage Pizza size Controller Routes ================*/
    Route::group(['prefix' => 'manage-pizza-size'], function () {
        Route::get('', 'PizzaSizeController@index');
        Route::get('list', 'PizzaSizeController@getPizzaSizeList');
        Route::post('add', 'PizzaSizeController@addPizzaSize');
        Route::post('update', 'PizzaSizeController@updatePizzaSize');
        Route::get('detail/{id}', 'PizzaSizeController@getPizzaSizeDetails');
        Route::get('view/{id}', 'PizzaSizeController@viewPizzaSize');
        Route::post('change-status', 'PizzaSizeController@changeStatus');
        Route::delete('delete/{id}', 'PizzaSizeController@deletePizzaSize');
    });


    /*================ Start Manage Pizza Cheese Controller Routes ================*/
    Route::group(['prefix' => 'manage-pizza-cheese'], function () {
        Route::get('', 'PizzaSizeController@pizzaCheese');
        Route::get('list', 'PizzaSizeController@getPizzaCheeseList');
        Route::post('add', 'PizzaSizeController@addPizzaCheese');
        Route::post('update', 'PizzaSizeController@updatePizzaCheese');
        Route::get('detail/{id}', 'PizzaSizeController@getPizzaCheeseDetails');
        Route::get('view/{id}', 'PizzaSizeController@viewPizzaCheese');
        Route::post('change-status', 'PizzaSizeController@changePizzaCheeseStatus');
        Route::delete('delete/{id}', 'PizzaSizeController@deletePizzaCheese');
    });


    /*================ Start Manage Size Master Controller Routes ================*/
    Route::group(['prefix' => 'manage-size-master'], function () {
        Route::get('', 'SizeMasterController@index');
        Route::get('list', 'SizeMasterController@getSizeMasterList');
        Route::post('add', 'SizeMasterController@addSizeMaster');
        Route::post('update', 'SizeMasterController@updateSizeMaster');
        Route::get('detail/{id}', 'SizeMasterController@getSizeMasterDetails');
        Route::get('view/{id}', 'SizeMasterController@viewSizeMaster');
        Route::post('change-status', 'SizeMasterController@changeStatus');
        Route::delete('delete/{id}', 'SizeMasterController@deleteSizeMaster');
    });

    /*================ Start Manage Store Master Controller Routes active================*/
    Route::group(['prefix' => 'manage-store'], function () {
        Route::get('', 'StoreMasterController@index');
        Route::get('list', 'StoreMasterController@index');
        Route::get('list-data', 'StoreMasterController@getStoreMasterList');
        Route::get('add', 'StoreMasterController@addStoreMaster');
        Route::post('save', 'StoreMasterController@saveStoreMaster');
        Route::get('edit/{id}', 'StoreMasterController@getEditStoreMasterDetails');

        Route::post('update', 'StoreMasterController@updateStoreMaster');
        Route::get('detail/{id}', 'StoreMasterController@getStoreMasterDetails');
        Route::get('view/{id}', 'StoreMasterController@viewStoreMaster');
        Route::post('change-status', 'StoreMasterController@changeStatus');
        Route::delete('delete/{id}', 'StoreMasterController@deleteStoreMaster');
    });

    /*================ Start Manage Product Master Controller Routes active================*/
    Route::group(['prefix' => 'product'], function () {
        Route::get('', 'ProductController@index');
        Route::get('list', 'ProductController@index');
        Route::get('list-data', 'ProductController@getProductList');
        Route::get('add', 'ProductController@addProduct');
        Route::post('save', 'ProductController@saveProduct');
        Route::get('edit/{id}', 'ProductController@getEditProductDetails');

        Route::post('update', 'ProductController@updateProduct');
        Route::get('detail/{id}', 'ProductController@getProductDetails');
        Route::get('view/{id}', 'ProductController@viewProduct');
        Route::post('change-status', 'ProductController@changeStatus');
        Route::delete('delete/{id}', 'ProductController@deleteProduct');
        Route::get('sub-category/{id}','ProductController@getSubCategoryList' );
    });

    /*================ Start Manage Drink Master Controller Routes active================*/
    Route::group(['prefix' => 'manage-drink-master'], function () {
        Route::get('', 'DrinkMasterController@index');
        Route::get('list', 'DrinkMasterController@getDrinkMasterList');
        Route::post('add', 'DrinkMasterController@addDrinkMaster');
        Route::post('update', 'DrinkMasterController@updateDrinkMaster');
        Route::get('detail/{id}', 'DrinkMasterController@getDrinkMasterDetails');
        Route::get('view/{id}', 'DrinkMasterController@viewDrinkMaster');
        Route::post('change-status', 'DrinkMasterController@changeStatus');
        Route::delete('delete/{id}', 'DrinkMasterController@deleteDrinkMaster');
    });

    /*================ Start Manage Topping Dips Controller Routes ================*/
    Route::group(['prefix' => 'manage-topping-dips'], function () {
        Route::get('', 'ToppingDipsController@index');
        Route::get('list', 'ToppingDipsController@getToppingDipsList');
        Route::post('add', 'ToppingDipsController@addToppingDips');
        Route::post('update', 'ToppingDipsController@updateToppingDips');
        Route::get('detail/{id}', 'ToppingDipsController@getToppingDipsDetails');
        Route::get('view/{id}', 'ToppingDipsController@viewToppingDips');
        Route::post('change-status', 'ToppingDipsController@changeStatus');
        Route::delete('delete/{id}', 'ToppingDipsController@deleteToppingDips');
    });


     /*================ Start Manage Topping Dips Controller Routes ================*/
    Route::group(['prefix' => 'manage-topping-pizza'], function () {
        Route::get('', 'ToppingPizzaController@index');
        Route::get('list', 'ToppingPizzaController@getToppingPizzaList');
        Route::post('add', 'ToppingPizzaController@addToppingPizza');
        Route::post('update', 'ToppingPizzaController@updateToppingPizza');
        Route::get('detail/{id}', 'ToppingPizzaController@getToppingPizzaDetails');
        Route::get('view/{id}', 'ToppingPizzaController@viewToppingPizza');
        Route::post('change-status', 'ToppingPizzaController@changeStatus');
        Route::delete('delete/{id}', 'ToppingPizzaController@deleteToppingPizza');
    });

     /*================ Start Manage Topping Dips Controller Routes ================*/
    Route::group(['prefix' => 'manage-topping-wing-flavour'], function () {
        Route::get('', 'ToppingWingFlavourController@index');
        Route::get('list', 'ToppingWingFlavourController@getToppingWingFlavourList');
        Route::post('add', 'ToppingWingFlavourController@addToppingWingFlavour');
        Route::post('update', 'ToppingWingFlavourController@updateToppingWingFlavour');
        Route::get('detail/{id}', 'ToppingWingFlavourController@getToppingWingFlavourDetails');
        Route::get('view/{id}', 'ToppingWingFlavourController@viewToppingWingFlavour');
        Route::post('change-status', 'ToppingWingFlavourController@changeStatus');
        Route::delete('delete/{id}', 'ToppingWingFlavourController@deleteToppingWingFlavour');
    });

     /*================ Start Manage Topping Dips Controller Routes ================*/
    Route::group(['prefix' => 'manage-topping-donair-shawarma-mediterranean'], function () {
        Route::get('', 'ToppingDonairShawarmaMediterraneanController@index');
        Route::get('list', 'ToppingDonairShawarmaMediterraneanController@getToppingDonairShawarmaMediterraneanList');
        Route::post('add', 'ToppingDonairShawarmaMediterraneanController@addToppingDonairShawarmaMediterranean');
        Route::post('update', 'ToppingDonairShawarmaMediterraneanController@updateToppingDonairShawarmaMediterranean');
        Route::get('detail/{id}', 'ToppingDonairShawarmaMediterraneanController@getToppingDonairShawarmaMediterraneanDetails');
        Route::get('view/{id}', 'ToppingDonairShawarmaMediterraneanController@viewToppingDonairShawarmaMediterranean');
        Route::post('change-status', 'ToppingDonairShawarmaMediterraneanController@changeStatus');
        Route::delete('delete/{id}', 'ToppingDonairShawarmaMediterraneanController@deleteToppingDonairShawarmaMediterranean');
    });

     /*  Food Cart API  */
    /*================ Start Manage Order Routes ================*/
    Route::group(['prefix' => 'orders'], function () {
        Route::get('', 'OrderController@index');
        Route::get('list-data', 'OrderController@getOrderList');
        Route::post('add', 'OrderController@addOrder');
        Route::post('update', 'OrderController@updateOrder');
        Route::get('view/{id}', 'OrderController@viewOrder');
        Route::get('edit/{id}', 'OrderController@getOrderDetails');
        Route::post('change-status', 'OrderController@changeStatus');
        Route::delete('delete/{id}', 'OrderController@deleteOrder');
        Route::get('print/{id}', 'OrderController@printOrder');

    });
    Route::group(['prefix' => 'manage-social'], function () {
        Route::get('edit', 'UserController@getSocialLinks');
        Route::post('update', 'UserController@updateSocialLinks');
        
    });

    


});
