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



Route::get('/register', function () {
    return view('home');
});

Route::any('/', ['as' => 'user_home', 'uses' => 'UserController@index']);

Route::any('/user_home', ['as' => 'user_home', 'uses' => 'UserController@index']);
Route::any('/add_user', ['as' => 'add_user', 'uses' => 'UserController@add']);
Route::any('/edit_user/{id}', ['as' => 'edit_user', 'uses' => 'UserController@edit']);
Route::get('/delete_user/{id}', ['as' => 'delete_user', 'uses' => 'UserController@delete']);

Route::any('/vehicle_home', ['as' => 'vehicle_home', 'uses' => 'VehicleController@index']);
Route::any('/add_vehicle', ['as' => 'add_vehicle', 'uses' => 'VehicleController@add']);
Route::any('/edit_vehicle/{id}', ['as' => 'edit_vehicle', 'uses' => 'VehicleController@edit']);
Route::get('/delete_vehicle/{id}', ['as' => 'delete_vehicle', 'uses' => 'VehicleController@delete']);

Route::any('/user_checker_home', ['as' => 'user_checker_home', 'uses' => 'CheckerController@index']);
Route::any('/add_user_checker', ['as' => 'add_user_checker', 'uses' => 'CheckerController@add']);
Route::any('/edit_user_checker/{id}', ['as' => 'edit_user_checker', 'uses' => 'CheckerController@edit']);
Route::get('/delete_user_checker/{id}', ['as' => 'delete_user_checker', 'uses' => 'CheckerController@delete']);

Route::any('/toilet_home', ['as' => 'toilet_home', 'uses' => 'ToiletController@index']);
Route::any('/add_toilet', ['as' => 'add_toilet', 'uses' => 'ToiletController@add']);
Route::any('/edit_toilet/{id}', ['as' => 'edit_toilet', 'uses' => 'ToiletController@edit']);
Route::get('/delete_toilet/{id}', ['as' => 'delete_toilet', 'uses' => 'ToiletController@delete']);

Route::any('/assign_toilet_home', ['as' => 'assign_toilet_home', 'uses' => 'AssignToiletController@index']);
Route::any('/assign_toilet', ['as' => 'assign_toilet', 'uses' => 'AssignToiletController@add']);
Route::get('/delete_assign_toilet/{id}', ['as' => 'delete_assign_toilet', 'uses' => 'AssignToiletController@delete']);
Route::get('/download_report/{id}', ['as' => 'download_report', 'uses' => 'AssignToiletController@download_report']);


Route::any('/report_home', ['as' => 'report_home', 'uses' => 'AssignToiletController@report']);

Route::any('/expense_home', ['as' => 'expense_home', 'uses' => 'AssignToiletController@expenseReport']);



Auth::routes();

Route::get('/home', ['as' => 'user_home', 'uses' => 'UserController@index'])->name('home');
