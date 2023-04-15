<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    return (new ApiController())->login($request);
});

Route::group([
    'middleware' => 'authenticate_request'
], function () {

    Route::any('/vehiclesList', function () {
        return (new ApiController())->vehiclesList();
    });

    Route::any('/assignVehicleToUser', function (Request $request) {
        return (new ApiController())->assignVehicleToUser($request);
    });

    Route::any('/addExpense', function (Request $request) {
        return (new ApiController())->addExpense($request);
    });

    Route::any('/getExpense', function (Request $request) {
        return (new ApiController())->getExpense($request);
    });

    Route::any('/getToiletList', function (Request $request) {
        return (new ApiController())->getToiletList($request);
    });

    Route::any('/uploadFile', function (Request $request) {
        return (new ApiController())->uploadFile($request);
    });

    Route::any('/reportToilet', function (Request $request) {
        return (new ApiController())->reportToilet($request);
    });

});

Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {
    //    Route::resource('task', 'TasksController');

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_api_routes
});

