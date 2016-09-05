<?php

Route::group(['namespace' => 'Gnosis', 'prefix' => 'cms'], function () {

    // Guest users only
    Route::group(['middleware' => 'guest'], function () {

        // Auth
        Route::get('/login', 'AuthController@getLogin')->name('login.get');
        Route::post('/login', 'AuthController@postLogin')->name('login.post');
    });

    // CMS users only
    Route::group(['middleware' => ['auth', 'can:cms']], function () {

        // Auth
        Route::post('/logout', 'AuthController@logout')->name('logout');
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::resource('users', 'UserController');
    });
});
