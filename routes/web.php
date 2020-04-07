<?php

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
	$user = Auth::user();
	if(isset($user->id)){
		return redirect('/admin');
	}
    return view('auth.login');
});

/* ================== Homepage + Admin Routes ================== */

require __DIR__.'/admin_routes.php';