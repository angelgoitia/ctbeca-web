<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::fallback(function () {
    return redirect()->route('welcome');
});

Route::get('/admin/login', function () {
    if (Auth::guard('web')->check()){
        return redirect(route('player.dashboard'));
    }elseif (Auth::guard('admin')->check()){
        return redirect(route('admin.dashboard'));
    }

    return view('auth.login')->with('type', 0);
})->name('admin.login');

Route::get('/login', function () {
    if (Auth::guard('web')->check()){
        return redirect(route('player.dashboard'));
    }elseif (Auth::guard('admin')->check()){
        return redirect(route('admin.dashboard'));
    }

    return view('auth.login')->with('type', 1);
})->name('player.login');

Route::post('admin/login/', 'AdminController@login')->name('formAdmin.login');
Route::post('login/form', 'PlayerController@login')->name('formPlayer.login');
Route::get('logout/', 'AdminController@logout')->name('logout');
Route::post('logout/', 'AdminController@logout')->name('logout');

Route::group(['middleware'=>'web'], function() {
    Route::get('/inicio/', 'PlayerController@dashboard')->name('player.dashboard');
});

Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/', 'AdminController@dashboard')->name('admin.dashboard');
    Route::post('/admin/dataGraphic', 'AdminController@dataGraphic')->name('admin.dataGraphic');
    Route::get('/admin/becados', 'AdminController@listPlayers')->name('admin.listPlayers');
    Route::post('/admin/becados/form', 'AdminController@formPlayer')->name('admin.formPlayer');
    Route::post('/admin/becados/show', 'AdminController@showPlayer')->name('admin.showPlayer');
    Route::post('/admin/juego', 'AdminController@listDaily')->name('admin.listDaily');
    Route::get('/admin/juego', 'AdminController@listDaily')->name('admin.listDaily');
    
    Route::get('/admin/api', 'AdminController@apiSLP')->name('admin.apiSLP');
});
