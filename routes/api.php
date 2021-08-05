<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::get('loginAdmin', 'AuthController@loginAdmin');
    Route::get('loginPlayer', 'AuthController@loginlayer');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('admin', 'AuthController@admin');
        Route::get('player', 'AuthController@player');
    });

});
