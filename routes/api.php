<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

//simple access route
Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {

    
    /**
     * user
     */
    Route::resource('users', 'User\UserController', ['only' => ['store']]);
    /**
     * Categories
     */
    Route::resource('categories', 'Category\CategoryController', ['only' => ['index', 'show']]);
    /**
     * Products
     */
    Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);

       /**
     * image
     */
    Route::resource('pictures', 'Picture\PictureController', ['only' => ['index', 'show']]);


    Route::resource('secteurs', 'Secteur\SecteurController', ['only' => ['index', 'show']]);

    /**
     * carousel
     */
    Route::resource('carousels', 'Carousel\CarouselController', ['only' => ['index', 'show']]);
  

    //login
    Route::post('login', 'User\UserController@login');
    Route::get('signup/activate/{token}', 'User\UserController@signupActivate');
   
    Route::post('sendmail', 'MailController@sendEmail');
  
    Route::get('customer','MailController@sendOrderEmail');
    //forgot password make
    Route::post('create', 'Security\ForgotPassword@create');
    Route::get('find/{token}', 'Security\ForgotPassword@find');
    Route::post('reset', 'Security\ForgotPassword@reset');
   
});


//login access
Route::group(['prefix' => 'v1/', 'middleware' => 'auth:api'], function () {
    Route::post('change_password', 'User\UserController@change_password');
    /**
     * user
     */
    Route::resource('users', 'User\UserController', ['only' => ['destroy', 'index', 'show','update']]);
    /**
     * Buyers
     */
    Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
    Route::resource('buyersproducts', 'Buyer\BuyerProductController', ['only' => ['index']]);
    Route::resource('buyerstransactions', 'Buyer\BuyerTransactionController', ['only' => ['index']]);

    /**
     * Categories
     */
    Route::resource('categories', 'Category\CategoryController', ['only' => ['store', 'update', 'destroy']]);

    
    /**
     * Products
     */
    Route::resource('products', 'Product\ProductController', ['only' => ['store', 'update', 'destroy']]);

    /**
     * carousel
     */
    Route::resource('carousels', 'Carousel\CarouselController', ['only' => ['store', 'update', 'destroy']]);

    /**
     * Sallers
     */
    Route::resource('sallers', 'Saller\SallerController', ['only' => ['index', 'show']]);


    /**
     * Transactions
     */
    Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show', 'store', 'destroy', 'update']]);
    /**
     * Transactions subcategorie
     */
    Route::resource('transactions.subcategory', 'Transaction\TransactionCategoryController', ['only' => ['index']]);

    /**
     * image
     */
    Route::resource('pictures', 'Picture\PictureController', ['only' => ['store', 'update', 'destroy']]);

     
    Route::resource('secteurs', 'Secteur\SecteurController', ['only' => ['store', 'update', 'destroy']]);


     //logout
     Route::get('logout', 'User\UserController@logout');
});
