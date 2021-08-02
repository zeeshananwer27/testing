<?php

Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');

Route::get('app_cont', 'DashboardController@app_signup');

Route::get('/sales-analytics', [
    'as' => 'admin.sales_analytics.index',
    'uses' => 'SalesAnalyticsController@index',
    'middleware' => 'can:admin.orders.index',
]);

