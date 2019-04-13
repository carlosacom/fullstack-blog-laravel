<?php
Route::post('login','Usercontroller@login');
Route::post('register','Usercontroller@store');
Route::group(['middleware' => ['jwtAuth']], function() {
    Route::resource('/user','UserController');
});
Route::resource('/category', 'CategoryController');
Route::resource('/roles', 'RoleController');
Route::resource('/posts', 'PostController');