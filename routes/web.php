<?php
Route::post('login','UserController@login');
Route::post('register','UserController@store');
Route::group(['middleware' => ['jwtAuth']], function() {
    Route::post('/user/upload', 'UserController@upload');
    Route::put('/user', 'UserController@update');
    // Route::resource('/user','UserController');
});
Route::resource('/category', 'CategoryController');
Route::resource('/roles', 'RoleController');
Route::resource('/posts', 'PostController');