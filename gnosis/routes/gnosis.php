<?php

Route::group(['namespace' => 'Gnosis', 'prefix' => 'cms'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard-index');

    Route::resource('users', 'UserController');
});

/**
 * Debug Routes
 */

// Creates a new user

// Route::group(['prefix' => 'debug'], function () {
//     Route::get('user', function () {
//         return App\Models\Gnosis\User::create([
//             'name'     => 'admin',
//             'email'    => 'example@gnosis.xyz',
//             'password' => bcrypt('password'),
//         ]);
//     });
// });
