<?php

// $exitCode = Artisan::call('vendor:publish');

// $exitCode = Artisan::call('config:clear');
// print_r($exitCode);
// Route::get('/clear-cache', function() {
// $exitCode = Artisan::call('config:clear');
// $exitCode = Artisan::call('cache:clear');
// $exitCode = Artisan::call('config:cache');
// return 'DONE'; //Return anything
// });
// exit('here');
Route::get('/', 'HomeController@index')->name('home');

Route::get('event', 'ApiController@events');

Route::get('sitting_arrangements/{id}', 'ApiController@sitting_arrangements'); // sitting arrangements
Route::get('advance_option', 'ApiController@advance_option')->name('advance_option'); // advance option
Route::get('advance_values', 'ApiController@advance_values')->name('advance_values'); // advance option
Route::get('show_templates', 'ApiController@show_templates')->name('show_templates'); // fetch data from json file
Route::get('detele_templates', 'ApiController@detele_templates')->name('detele_templates'); // detele_templates data from json file
Route::get('reservedSeatsData', 'ApiController@reservedSeatsData')->name('reservedSeatsData');

Route::post('sitting_arrangement_save', 'ApiController@save_sitting')->name('sitting_arrangement_save'); // save sitting arrangements

Route::post('customerlist', 'ApiController@list_of_customers');

Route::post('scanner', 'ApiController@scanner_password');

Route::post('checkout_mob', 'ApiController@checkout_mob');

Route::post('OrderHistory', 'ApiController@order_history');

Route::post('QrCodeScann', 'ApiController@qrcode_scann');

Route::get('active', 'HomeController@active');

Route::get('directory', 'HomeController@directory');


