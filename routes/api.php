<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'AuthController@login');
    Route::post('version', 'AuthController@VersionApp');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('admin', 'AuthController@admin');
        Route::get('player', 'AuthController@player');
        Route::post('updateToken', 'AuthController@updateToken');
        Route::post('formPlayer', 'AdminController@formPlayer');
    });

    Route::group(['middleware' => 'auth:player'], function() {
        Route::post('logoutPlayer', 'AuthController@logout');
        Route::get('player', 'AuthController@player');
    });

});
