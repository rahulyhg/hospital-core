<?php

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


Route::post('register', 'Api\V1\AuthController@register');


Route::group(['middleware'=>'cors', 'namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        
    });
    
    Route::get('patient', 'SamplePatientController@index');
    Route::get('patient/{id}', 'SamplePatientController@show');
    Route::post('patient', 'SamplePatientController@store');
    Route::post('patient/{id}', 'SamplePatientController@update');
    Route::delete('patient/{id}', 'SamplePatientController@delete');
    
    

    
    Route:: group(['prefix' => 'dontiep'], function () {
        Route::get('quetthe/{bhytcode}','DonTiep\BhytController@getTypePatientByCode');
        Route::get('patient', 'DonTiep\PatientController@index');
        //Route::post('patient/register', 'SamplePatientController@register');
        Route::get('typepatient/{patientid}', 'DonTiep\HosobenhanController@typePatient');
        //Route::get('typepatient', 'PatientController@typePatient');
    });
    Route:: group(['prefix' => 'dangkykhambenh'], function () {
        Route::post('dangky', 'DangKyKhamBenhController@dangky');
    });
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');