<?php

Route::get('images/{filename}', function ($filename) {
    $image = new \UploadImage();
    return $image->getImage($filename);    
});
Route::get('posts/category/{category_id}', 'PostController@getPostsForCategory');
Route::get('posts/user/{user_id}', 'PostController@getPostsForUser');
Route::post('login','UserController@login');
Route::post('register','UserController@store');
Route::post('posts/image/{id}','PostController@uploadImage');
Route::group(['middleware' => ['jwtAuth']], function() {
    Route::get('user/{id}','UserController@show');
    Route::post('/user/upload', 'UserController@upload');
    Route::put('/user', 'UserController@update');
});
Route::resource('/category', 'CategoryController');
Route::resource('/posts', 'PostController');
// Route::resource('/roles', 'RoleController');