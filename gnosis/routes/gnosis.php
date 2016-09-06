<?php

Route::group(['namespace' => 'Gnosis', 'prefix' => 'cms'], function () {

    // Guest users only
    Route::group(['middleware' => 'guest'], function () {

        // Auth
        Route::get('/login', 'AuthController@getLogin')->name('login.get');
        Route::post('/login', 'AuthController@postLogin')->name('login.post');
        Route::get('/forgotten', 'AuthController@getForgotten')->name('forgotten.get');
        Route::post('/forgotten', 'AuthController@postForgotten')->name('forgotten.post');
        Route::get('/reset/{token}', 'AuthController@getReset')->name('reset.get');
        Route::post('/reset', 'AuthController@postReset')->name('reset.post');
    });

    // General Authorised users
    Route::group(['middleware' => ['auth']], function () {
        // Auth
        Route::get('/logout', 'AuthController@logout')->name('logout');
    });

    // CMS users only
    Route::group(['middleware' => ['auth', 'can:cms']], function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::resource('users', 'UserController');
    });
});

// Form Macros
Form::macro('error', function ($key, $errors, $formName = false) {
    if (old('__form') === $formName && count($errors) > 0) {
        return $errors->has($key) ? 'form__field--error' : '';
    }
});

Form::macro('errorMessage', function ($key, $errors, $formName = false) {
    if (old('__form') === $formName && count($errors) > 0) {
        return $errors->has($key) ? "data-error=\"" . $errors->first($key) . "\"" : '';
    }
});
