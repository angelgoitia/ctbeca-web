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

Route::fallback(function () {
    return redirect()->route('welcome');
});

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
Route::post('player/login/', 'PlayerController@login')->name('formPlayer.login');
Route::get('logout/', 'AdminController@logout')->name('logout');
Route::post('logout/', 'AdminController@logout')->name('logout');

Route::group(['middleware'=>'web'], function() {
    Route::get('/becado/inicio/', 'PlayerController@dashboard')->name('player.dashboard');
    Route::post('/player/dataGraphic', 'AdminController@dataGraphic')->name('admin.dataGraphic');
    Route::get('/becado/perfil/', 'PlayerController@profile')->name('player.profile');
    Route::post('/becado/historial/juego', 'PlayerController@listDaily')->name('player.listDaily');
});

Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/', 'AdminController@dashboard')->name('admin.dashboard');
    Route::post('/admin/dataGraphic', 'AdminController@dataGraphic')->name('admin.dataGraphic');
    Route::get('/admin/becados', 'AdminController@listPlayers')->name('admin.listPlayers');
    Route::post('/admin/becado/form', 'AdminController@formPlayer')->name('admin.formPlayer');
    Route::post('/admin/becado/show', 'AdminController@showPlayer')->name('admin.showPlayer');
    Route::post('/admin/becado/edit', 'AdminController@editPlayer')->name('admin.editPlayer');
    Route::post('/admin/historial/juego', 'AdminController@listDaily')->name('admin.listDaily');
    Route::get('/admin/historial/juego', 'AdminController@listDaily')->name('admin.listDaily');
    Route::get('admin/nuevo/slp', 'AdminController@newSLP')->name('admin.newSLP');
    Route::post('admin/nuevo/slp', 'AdminController@formSLP')->name('admin.formSLP');
    Route::post('admin/verify/slp', 'AdminController@verifySLP')->name('admin.verifySLP');
    Route::get('admin/tasas', 'AdminController@rates')->name('admin.rates');
    Route::get('admin/tasa', 'AdminController@rate')->name('admin.rate');
    Route::post('admin/tasa/form', 'AdminController@formRate')->name('admin.formRate');
    
    Route::get('/admin/api', 'AdminController@apiSLP')->name('admin.apiSLP');
});
