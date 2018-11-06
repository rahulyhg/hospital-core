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
        

    
    Route::group(['prefix' => 'dontiep'], function () {
        Route::post('makeSttDonTiepWhenScanCard','DonTiep\SttDonTiepController@makeSttDonTiepWhenScanCard');
        Route::post('scanCard','DonTiep\SttDonTiepController@scanCard');
        Route::get('getSttDonTiep','DonTiep\SttDonTiepController@getSttDonTiep');
        Route::get('goiSttDonTiep','DonTiep\SttDonTiepController@goiSttDonTiep');
        Route::get('loadSttDonTiep','DonTiep\SttDonTiepController@loadSttDonTiep');
        Route::get('finishSttDonTiep/{sttId}','DonTiep\SttDonTiepController@finishSttDonTiep');
        Route::get('countSttDonTiep','DonTiep\SttDonTiepController@countSttDonTiep');
        
        Route::get('getListPatientByKhoaPhong/{phongId}/{benhVienId}','DonTiep\DonTiepController@getListPatientByKhoaPhong');
        Route::get('getHsbaByHsbaId/{hsbaId}/{phongId}','DonTiep\DonTiepController@getHsbaByHsbaId');
        Route::post('updateInfoPatient/{hsbaId}','DonTiep\DonTiepController@updateInfoPatient');
        
        Route::post('scanqrcode', 'DonTiep\ScanQRCodeController@getInfoFromCard');
        Route::post('register','DonTiep\DonTiepController@register');
        Route::post('chuyenKhoaPhong','DonTiep\DonTiepController@chuyenKhoaPhong');

    });
    
    Route::group(['prefix' => 'dangkykhambenh'], function () {
		Route::get('listPhong/{loaiPhong}/{khoaId}','DangKyKhamBenh\DangKyKhamBenhController@getListPhong');
    	Route::get('yeuCauKham/{loai_nhom}','DangKyKhamBenh\DangKyKhamBenhController@getListYeuCauKham');
    	Route::get('listNgheNghiep','DangKyKhamBenh\DangKyKhamBenhController@getListNgheNghiep');
    	Route::get('danhMucBenhVien','DangKyKhamBenh\DangKyKhamBenhController@danhMucBenhVien');
    	Route::get('listDanToc','DangKyKhamBenh\DangKyKhamBenhController@getListDanToc');
    	Route::get('listQuocTich','DangKyKhamBenh\DangKyKhamBenhController@getListQuocTich');
    	Route::get('listTinh','DangKyKhamBenh\DangKyKhamBenhController@getListTinh');
    	Route::get('listHuyen/{maTinh}','DangKyKhamBenh\DangKyKhamBenhController@getListHuyen');
    	Route::get('listXa/{maHuyen}/{maTinh}','DangKyKhamBenh\DangKyKhamBenhController@getListXa');
    	Route::get('benhVien','DangKyKhamBenh\DangKyKhamBenhController@benhVien');
    });
    
    Route::group(['prefix' => 'phongkham'], function () {
		
		
    });
    
    Route::group(['prefix' => 'danhmuc'], function () {
		Route::get('getListDanhMucDichVu','DanhMuc\DanhMucController@getListDanhMucDichVu');
		Route::get('getDmdvById/{dmdvId}','DanhMuc\DanhMucController@getDmdvById');
    	Route::post('createDanhMucDichVu','DanhMuc\DanhMucController@createDanhMucDichVu');
    	Route::post('updateDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@updateDanhMucDichVu');
    	Route::delete('deleteDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@deleteDanhMucDichVu');
    });
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');