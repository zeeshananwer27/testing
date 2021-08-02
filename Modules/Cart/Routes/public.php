<?php

Route::get('cart', 'CartController@index')->name('cart.index');

Route::post('cart/items', 'CartItemController@store')->name('cart.items.store');
Route::post('cart/ajaxitems', 'CartItemController@ajaxstore')->name('cart.items.ajaxstore');
Route::put('cart/items/{cartItemId}', 'CartItemController@update')->name('cart.items.update');

// Route::post('cart/items/{cartItemId}', 'CartItemController@update')->name('cart.items.update');

Route::delete('cart/items/{cartItemId}', 'CartItemController@destroy')->name('cart.items.destroy');

Route::post('cart/shipping-method', 'CartShippingMethodController@store')->name('cart.shipping_method.store');
