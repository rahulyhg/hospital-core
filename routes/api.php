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


//Route::post('register', 'Api\V1\AuthController@register');


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
        Route::post('checkCardCode','DonTiep\SttDonTiepController@checkCardCode');
        Route::get('getSttDonTiep','DonTiep\SttDonTiepController@getSttDonTiep');
        Route::get('goiSttDonTiep','DonTiep\SttDonTiepController@goiSttDonTiep');
        Route::get('loadSttDonTiep','DonTiep\SttDonTiepController@loadSttDonTiep');
        
        Route::get('getInfoPatientByStt/{stt}/{phong_id}/{benh_vien_id}','DonTiep\DonTiepController@getInfoPatientByStt');
        Route::get('getListPatientByKhoaPhong/{type}/{phong_id}','DonTiep\DonTiepController@getListPatientByKhoaPhong');
        Route::get('getHsbaByHsbaId/{hsba_id}/{phong_id}','DonTiep\DonTiepController@getHsbaByHsbaId');
        Route::post('scanqrcode', 'DonTiep\ScanQRCodeController@getInfoFromCard');
        Route::post('register','DonTiep\DonTiepController@register');

    });
    
    Route:: group(['prefix' => 'dangkykhambenh'], function () {
        Route::post('dangky', 'DangKyKhamBenhController@dangky');
		Route::get('listphong/{loaiphong}/{khoaid}','DangKyKhamBenh\DangKyKhamBenhController@getListPhong');
    	Route::get('yeucaukham/{servicegrouptype}','DangKyKhamBenh\DangKyKhamBenhController@getListYeuCauKham');
    	Route::get('listnghenghiep','DangKyKhamBenh\DangKyKhamBenhController@getListNgheNghiep');
    	Route::get('listbenhvien','DangKyKhamBenh\DangKyKhamBenhController@getListBenhVien');
    	Route::get('listdantoc','DangKyKhamBenh\DangKyKhamBenhController@getListDanToc');
    	Route::get('listquoctich','DangKyKhamBenh\DangKyKhamBenhController@getListQuocTich');
    	Route::get('listtinh','DangKyKhamBenh\DangKyKhamBenhController@getListTinh');
    	Route::get('listhuyen/{matinh}','DangKyKhamBenh\DangKyKhamBenhController@getListHuyen');
    	Route::get('listxa/{mahuyen}/{matinh}','DangKyKhamBenh\DangKyKhamBenhController@getListXa');
    });
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');