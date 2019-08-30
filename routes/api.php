<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Http\Controllers\GenreController;
use Http\Controllers\PurchaseController;
use Http\Controllers\UserController;

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
    return $request->user();
});
Route::get('/', 'HomeController@index');//->name('home.index');

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::get('/cart', 'CartController@add');//before payment warning Already User?+link withredirect to reg or login
Route::get('/restore/{user}/restoreUser', 'HomeController@restore');//no table 'trashed_user'!!!!!!!!!!!!!!!!!!!???????????
Route::post('/restore/{user}/restoreUser', 'HomeController@restore');
Route::get('/restore/{trashed_user}/restoreUser', 'HomeController@restore');
Route::get('books/{id}', 'Admin\BooksController@show')->name('books.show');   
Route::get('magazines/{id}', 'Admin\MagazinsController@show')->name('magazines.show');
Route::group(['middleware' => 'guest'], function(){
	Route::get('/verify/{token}', 'RegisterController@verify');
	//Route::get('/verification', ' VerificationController@resend')->name('verification.resend');	
});

Route::group(['middleware' => 'auth'], function(){
	Route::resource('profile', 'UserController')->only(['index', 'store', 'update', 'destroy']);
	Route::prefix('/orders/{order}')->group(function (){
		Route::prefix('purchases')->group(function (){
			Route::get('/toggleIsPausedSubPrice/{id}', 'PurchaseController@toggleIsPausedSubPrice');
			Route::get('/toggleIsPausedDiscontId/{id}', 'PurchaseController@toggleIsPausedDiscontId');
			Route::get('/toggleBuy/{id}', 'PurchaseController@toggleBuy');
			Route::get('/toggleBuyAll', 'PurchaseController@toggleBuyAll')->name('toggleBuyAll');//not work if index cart status_bought ==1		
		});
		Route::apiResource('purchases', 'PurchaseController');

		//Route::get('/verification', ' VerificationController@resend')->name('verification.resend');
		Route::get('/cart', 'PurchaseController@indexCart')->name('cart');
		Route::get('/purchasebuy', 'PurchaseController@buy')->name('purchases.buy');
		Route::get('/purchasesAll/destroy', 'PurchaseController@destroyAll');		
	});

	Route::get('orders/{orders_id}/{purchase_id}', 'OrderController@setPurchase');
	Route::resource('orders', 'OrderController')->only(['index', 'store', 'show']);
	Route::get('/verify/{token}', 'OrderController@verify');
});

Route::group(['prefix' => 'admin', 'namespace'=>'Admin', 'middleware' => 'admin'], function(){
	Route::prefix('books')->group(function (){
		Route::get('/toggleDiscontGlB/{id}', 'BooksController@toggleDiscontGlB');
		Route::get('/toggleVisibleGlBAll', 'BooksController@toggleVisibleGlBAll')->name('admin.books.toggleVisibleGlBAll');
		Route::get('/toggleDiscontIdB/{id}', 'BooksController@toggleDiscontIdB');
		Route::get('/toggleVisibleIdBAll', 'BooksController@toggleVisibleIdBAll')->name('admin.books.toggleVisibleIdBAll');
		Route::get('/toggleBookFormat/{id}', 'BooksController@toggleBookFormat');
		Route::get('/toggleSetPublished/{id}', 'BooksController@toggleSetPublished');
	});
	Route::post('book/{book}/{genre}', 'BooksController@addGenre');
    Route::get('/genre/{genre}', function (\App\Models\Genre $genre){
        $books = $genre->books;
        return $books->toJson();
    });
	Route::resource('books', 'BooksController')->except(['show']);
	Route::prefix('magazines')->group(function (){
		Route::get('/toggleSubPrice/{id}', 'MagazinesController@toggleSubPrice');
		Route::get('/toggleDiscontGlM/{id}', 'MagazinesController@toggleDiscontGlM');
		Route::get('/toggleVisibleGlMAll', 'MagazinesController@toggleVisibleGlMAll')->name('admin.magazines.toggleVisibleGlMAlll');
		Route::get('/toggleDiscontIdM/{id}', 'MagazinesController@toggleDiscontIdM');
		Route::get('/toggleVisibleIdMAll', 'MagazinesController@toggleVisibleIdMAll')->name('admin.magazines.toggleVisibleIdM');
		Route::get('/toggleSetPublished/{id}', 'MagazinesController@toggleSetPublished');
	});
	Route::post('magazines/{magazines}/{genre}', 'MagazinesController@addGenre');
    Route::get('/genre/{genre}', function (\App\Models\Genre $genre){
        $magazines = $genre->magazines;
        return $magazines->toJson();
    });
	Route::resource('magazines', 'MagazinesController')->except(['show']);
	Route::prefix('orders/{order}')->group(function (){
		Route::group(['prefix' => 'purchases'], function(){
			Route::get('/purchasesdaybefore', 'PurchasesController@indexDayBefore')->name('admin.purchases.indexDayBefore');
			Route::get('/purchasesweekbefore', 'PurchasesController@indexWeekBefore')->name('admin.purchases.indexWeekBefore');
			Route::get('/purchasesmonthbefore', 'PurchasesController@indexMonthBefore')->name('admin.purchases.indexMonthBefore');
		});
		Route::resource('purchases', 'PurchasesController')->only(['index']);
	});
	Route::resource('orders', 'OrdersController')->only(['index']);
	Route::prefix('/users')->group(function(){
			Route::get('/toggleUnVisibleDiscontIdAll/{id}', 'UsersController@toggleUnVisibleDiscontIdAll');
			Route::get('/toggleVisibleDiscontGlobal/{id}', 'UsersController@toggleVisibleDiscontGlobal');
			Route::get('/toggleVisibleDiscontGlobalAll/{id}', 'UsersController@toggleVisibleDiscontGlobalAll');
			Route::get('/toggleSubPrice/{id}', 'UsersController@toggleSubPrice');
			Route::get('/toggleAdmin/{id}', 'UsersController@toggleAdmin');
			Route::get('/toggleBan/{id}', 'UsersController@toggleBan');		
	});
	Route::apiResource('users', 'UsersController');
	Route::apiResource('genres', 'GenreController');
});
