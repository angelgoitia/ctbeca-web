<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::get('loginAdmin', 'AuthController@loginAdmin');
    Route::post('loginPlayer', 'AuthController@loginplayer');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

});
