<?php

Route::get('storefront', [
    'as' => 'admin.storefront.settings.edit',
    'uses' => 'StorefrontController@edit',
    'middleware' => 'can:admin.storefront.edit',
]);

// Route::get('storefront', [
//     'as' => 'admin.storefront.sitting',
//     'uses' => 'StorefrontController@sitting',
//     'middleware' => 'can:admin.storefront.sitting',
// ]);

Route::get('admin.storefront.sitting', 'StorefrontController@sitting')->name('admin.storefront.sitting');

Route::put('storefront', [
    'as' => 'admin.storefront.settings.update',
    'uses' => 'StorefrontController@update',
    'middleware' => 'can:admin.storefront.edit',
]);
