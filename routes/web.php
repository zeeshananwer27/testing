<?php

Route::get('install/pre-installation', 'InstallController@preInstallation');
Route::get('install/configuration', 'InstallController@getConfiguration');
Route::post('install/configuration', 'InstallController@postConfiguration');
Route::get('install/complete', 'InstallController@complete');

Route::get('/clear-cache', function() {
$exitCode = Artisan::call('view:clear');
$exitCode = Artisan::call('config:clear');
$exitCode = Artisan::call('cache:clear');
$exitCode = Artisan::call('config:cache'); 
return 'DONEAll'; //Return anything
});