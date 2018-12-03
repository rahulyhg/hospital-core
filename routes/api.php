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
        Route::get('getHsbaByHsbaId/{hsbaId}','DonTiep\DonTiepController@getByHsbaId');
        Route::post('updateInfoPatient/{hsbaId}','DonTiep\DonTiepController@updateInfoPatient');
        
        Route::post('scanqrcode', 'DonTiep\ScanQRCodeController@getInfoFromCard');
        Route::post('register','DonTiep\DonTiepController@register');
    });
    
    Route::group(['prefix' => 'setting'], function () {
        Route::get('khuVuc/{loai}/{benhVienId}','UserSetting\UserSettingController@getListKhuVuc');
        Route::get('quaySo/{khuVucId}/{benhVienId}','UserSetting\UserSettingController@getListQuay');        
    });
    
    Route::group(['prefix' => 'dangkykhambenh'], function () {
		Route::get('listPhong/{loaiPhong}/{khoaId}','DangKyKhamBenh\DangKyKhamBenhController@getListPhong');
		Route::get('listKhoa/{loaiKhoa}/{benhVienId}','DangKyKhamBenh\DangKyKhamBenhController@getListKhoa');
		Route::get('nhomPhongKham/{loaiPhong}/{khoaId}','DangKyKhamBenh\DangKyKhamBenhController@getNhomPhong');
    	Route::get('yeuCauKham/{loai_nhom}','DangKyKhamBenh\DangKyKhamBenhController@getListYeuCauKham');
    	Route::get('listNgheNghiep','DangKyKhamBenh\DangKyKhamBenhController@getListNgheNghiep');
    	Route::get('danhMucBenhVien','DangKyKhamBenh\DangKyKhamBenhController@danhMucBenhVien');
    	Route::get('listDanToc','DangKyKhamBenh\DangKyKhamBenhController@getListDanToc');
    	Route::get('listQuocTich','DangKyKhamBenh\DangKyKhamBenhController@getListQuocTich');
    	Route::get('listTinh','DangKyKhamBenh\DangKyKhamBenhController@getListTinh');
    	Route::get('listHuyen/{maTinh}','DangKyKhamBenh\DangKyKhamBenhController@getListHuyen');
    	Route::get('listXa/{maHuyen}/{maTinh}','DangKyKhamBenh\DangKyKhamBenhController@getListXa');
    	Route::get('benhVien','DangKyKhamBenh\DangKyKhamBenhController@benhVien');
    	Route::get('loaiVienPhi','DangKyKhamBenh\DangKyKhamBenhController@getListLoaiVienPhi');
    	Route::get('doiTuongBenhNhan','DangKyKhamBenh\DangKyKhamBenhController@getListDoiTuongBenhNhan');
    	Route::get('ketQuaDieuTri','DangKyKhamBenh\DangKyKhamBenhController@getListKetQuaDieuTri');
    	Route::get('giaiPhauBenh','DangKyKhamBenh\DangKyKhamBenhController@getListGiaiPhauBenh');
    	Route::get('xuTri','DangKyKhamBenh\DangKyKhamBenhController@getListXuTri');
    	Route::get('getLichSuKhamDieuTri/{benhNhanId}','DangKyKhamBenh\DangKyKhamBenhController@getLichSuKhamDieuTriByBenhNhanId');
    	Route::get('getListIcd10ByCode/{icd10Code}','DangKyKhamBenh\DangKyKhamBenhController@getListIcd10ByCode');
    	Route::get('bhytTreEm/{maTinh}','DangKyKhamBenh\DangKyKhamBenhController@getBhytTreEm');
    });
    
    Route::group(['prefix' => 'phongkham'], function () {
		Route::post('updateHsbaKhoaPhong/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@update');
		Route::get('getHsbaKhoaPhongById/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@getById');
		Route::post('updateInfoDieuTri','PhongKham\PhongKhamController@updateInfoDieuTri');
		Route::get('getListPhongKham/{hsbaId}','PhongKham\PhongKhamController@getListPhongKham');
		Route::post('xuTriBenhNhan','PhongKham\PhongKhamController@xuTriBenhNhan');
		Route::get('getIcd10ByCode/{icd10Code}','PhongKham\PhongKhamController@getIcd10ByCode');
    });
    
    Route::group(['prefix' => 'danhmuc'], function () {
		Route::get('getListDanhMucDichVu','DanhMuc\DanhMucController@getListDanhMucDichVu');
		Route::get('getDmdvById/{dmdvId}','DanhMuc\DanhMucController@getDmdvById');
    	Route::post('createDanhMucDichVu','DanhMuc\DanhMucController@createDanhMucDichVu');
    	Route::post('updateDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@updateDanhMucDichVu');
    	Route::delete('deleteDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@deleteDanhMucDichVu');
    	Route::get('getYLenhByLoaiNhom/{loaiNhom}','DanhMuc\DanhMucController@getYLenhByLoaiNhom');
    	Route::get('getDanhMucTongHopTheoKhoa/{khoa}','DanhMuc\DanhMucController@getDanhMucTongHopTheoKhoa');
    	Route::post('createDanhMucTongHop','DanhMuc\DanhMucController@createDanhMucTongHop');
    	Route::post('updateDanhMucTongHop/{dmthId}','DanhMuc\DanhMucController@updateDanhMucTongHop');
    	Route::delete('deleteDanhMucTongHop/{dmthId}','DanhMuc\DanhMucController@deleteDanhMucTongHop');
    	Route::get('getListDanhMucTrangThaiByKhoa/{khoa}','DanhMuc\DanhMucController@getListDanhMucTrangThaiByKhoa');
    	Route::get('getDmttById/{dmdvId}','DanhMuc\DanhMucController@getDmttById');
    	Route::post('createDanhMucTrangThai','DanhMuc\DanhMucController@createDanhMucTrangThai');
    	Route::post('updateDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@updateDanhMucTrangThai');
    	Route::delete('deleteDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@deleteDanhMucTrangThai');
    });
    
    Route::group(['prefix' => 'nguoidung'], function () {
		Route::get('getListNguoiDung','AuthUser\AuthUserController@getListNguoiDung');
 		Route::get('getAuthUsersById/{id}','AuthUser\AuthUserController@getAuthUsersById');
     	Route::post('createAuthUsers','AuthUser\AuthUserController@createAuthUsers');
     	Route::post('updateAuthUsers/{id}','AuthUser\AuthUserController@updateAuthUsers');
     	Route::delete('deleteAuthUsers/{id}','AuthUser\AuthUserController@deleteAuthUsers');
     	Route::get('checkEmail/{email}','AuthUser\AuthUserController@checkEmailbyEmail');
    });    
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');