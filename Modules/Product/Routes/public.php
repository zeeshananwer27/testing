<?php

Route::get('products', 'ProductController@index')->name('products.index');
Route::get('products/{slug}', 'ProductController@show')->name('products.show');

Route::get('advanced_options', 'AdvancedOptionController@update');
Route::get('delete_advance_option', 'AdvancedOptionController@destroy')->name('delete_advance_option');


Route::get('qrcode', function () {
 	return QrCode::size(300)->generate(json_encode(array('Name'=>'Hafiz Adil','Designation'=>'Software Engineer')));
});