<?php

Route::group(['namespace' => 'Gnosis', 'prefix' => 'cms'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard-index');
});
