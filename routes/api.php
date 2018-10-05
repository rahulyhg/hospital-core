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
        Route::get('quetthe/{bhytcode}','DonTiep\BhytController@getTypePatientByCode');
        Route::get('patient', 'DonTiep\PatientController@index');
        Route::post('taostt', 'DonTiep\RedSttDontiepController@insertCurrentSTTBT');
        Route::get('laystt', 'DonTiep\RedSttDontiepController@getCurrentSTTBT');
        Route::post('taosttut','DonTiep\RedSttDontiepController@insertCurrentSTTUT');
        Route::get('laysttut','DonTiep\RedSttDontiepController@getCurrentSTTUT');
        Route::get('laysttkm/{age}','DonTiep\RedSttDontiepController@getSTTKM');
        //Route::post('patient/register', 'SamplePatientController@register');
        Route::get('getHosobenhan/{patientid}', 'DonTiep\HosobenhanController@getHosobenhan');
        //Route::get('typepatient', 'PatientController@typePatient');
        
        
        Route::get('getInfoPatientByStt/{stt}/{id_phong}/{id_benh_vien}','DonTiep\DontiepController@getInfoPatientByStt');
        Route::get('getListPatientByKhoaPhong/{type}/{departmentid}','DonTiep\DontiepController@getListPatientByKhoaPhong');
        Route::get('getHSBAByHosobenhanID/{hosobenhanid}/{departmentid}','DonTiep\DontiepController@getHSBAByHosobenhanID');
		Route::post('scanqrcode', 'DonTiep\ScanQRCodeController@getInfoFromCard');
        Route::post('register','DonTiep\PatientController@register');

    });
    
    Route:: group(['prefix' => 'dangkykhambenh'], function () {
        Route::post('dangky', 'DangKyKhamBenhController@dangky');
		Route::get('listphong/{departmenttype}/{departmentgroupid}','DangKyKhamBenh\DangKyKhamBenhController@getListPhong');
    	Route::get('yeucaukham/{servicegrouptype}','DangKyKhamBenh\DangKyKhamBenhController@getListYeuCauKham');
    	Route::get('listnghenghiep','DangKyKhamBenh\DangKyKhamBenhController@getListNgheNghiep');
    	Route::get('listbenhvien','DangKyKhamBenh\DangKyKhamBenhController@getListBenhVien');
    	Route::get('listdantoc','DangKyKhamBenh\DangKyKhamBenhController@getListDanToc');
    	Route::get('listquoctich','DangKyKhamBenh\DangKyKhamBenhController@getListQuocTich');
    	Route::get('listtinh','DangKyKhamBenh\DangKyKhamBenhController@getListTinh');
    });
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');