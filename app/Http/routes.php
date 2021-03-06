<?php

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'MainController@index');

Route::group([
    'prefix'     => 'api',
    'middleware' => ['jsonApi'],
], function () {
    Route::resource('tags', 'TagController', ['except' => ['create', 'edit']]);
    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::resource('games', 'GameController', ['except' => ['create', 'edit']]);
    Route::get('user', function () {
        if (!Auth::check()) {
            abort(401);
        }

        return app()->call([app(UserController::class), 'show'], ['id' => Auth::user()->id]);
    });
    Route::match(['put', 'patch'], 'user', function () {
        if (!Auth::check()) {
            abort(401);
        }

        return app()->call([app(UserController::class), 'update'], ['id' => Auth::user()->id]);
    });

    Route::get('games/{games}/tags', 'GameTagController@index');
    Route::post('games/{games}/tags', 'GameTagController@store');
    Route::delete('games/{games}/tags/{tags}', 'GameTagController@destroy');

    Route::get('games/{games}/shared_with', 'GameSharedController@index');
    Route::post('games/{games}/shared_with', 'GameSharedController@store');
    Route::delete('games/{games}/shared_with/{users}', 'GameSharedController@destroy');

    Route::get('tags/{tags}/shared_with', 'TagSharedController@index');
    Route::post('tags/{tags}/shared_with', 'TagSharedController@store');
    Route::delete('tags/{tags}/shared_with/{users}', 'TagSharedController@destroy');
});
