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
        
        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'AuthUser\AuthPasswordResetController@create');
            Route::get('find/{token}', 'AuthUser\AuthPasswordResetController@find');
            Route::post('reset', 'AuthUser\AuthPasswordResetController@reset');
        });
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
        
        // store to cache from queue
        Route::post('hsbaKp/cache/fromQueue','DonTiep\DonTiepController@pushToRedisFromQueue');
        
    });
    
    Route::group(['prefix' => 'setting'], function () {
        Route::get('khuVuc/{loai}/{benhVienId}','UserSetting\UserSettingController@getListKhuVuc');
        Route::get('quaySo/{khuVucId}/{benhVienId}','UserSetting\UserSettingController@getListQuay');
        Route::get('getKhoaPhongByUserId/{userId}/{benhVienId}','AuthController@getKhoaPhongByUserId');
    });
    
    Route::group(['prefix' => 'dangkykhambenh'], function () {
		Route::get('listPhong/{loaiPhong}/{khoaId}','DangKyKhamBenh\DangKyKhamBenhController@getListPhong');
		Route::get('listKhoa/{loaiKhoa}/{benhVienId}','DangKyKhamBenh\DangKyKhamBenhController@getListKhoa');
		Route::get('listKhoaByBenhVienId/{benhVienId}','DangKyKhamBenh\DangKyKhamBenhController@listKhoaByBenhVienId');
		Route::get('nhomPhongKham/{loaiPhong}/{khoaId}','DangKyKhamBenh\DangKyKhamBenhController@getNhomPhong');
    	Route::get('yeuCauKham/{loai_nhom}','DangKyKhamBenh\DangKyKhamBenhController@getListYeuCauKham');
    	Route::get('listNgheNghiep','DangKyKhamBenh\DangKyKhamBenhController@getListNgheNghiep');
    	Route::get('danhMucBenhVien','DangKyKhamBenh\DangKyKhamBenhController@danhMucBenhVien');
    	Route::get('listDanToc','DangKyKhamBenh\DangKyKhamBenhController@getListDanToc');
    	Route::get('listQuocTich','DangKyKhamBenh\DangKyKhamBenhController@getListQuocTich');
    	Route::get('getTinhHuyenXa/{thxKey}','DangKyKhamBenh\DangKyKhamBenhController@getThxByKey');
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
        Route::get('goiSttPhongKham','PhongKham\SttPhongKhamController@goiSttPhongKham');
        Route::get('loadSttPhongKham','PhongKham\SttPhongKhamController@loadSttPhongKham');
        Route::get('finishSttPhongKham/{sttId}','PhongKham\SttPhongKhamController@finishSttPhongKham');
        Route::get('batDauKham/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@batDauKham');
		Route::post('updateHsbaKhoaPhong/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@update');
		Route::get('getHsbaKhoaPhongById/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@getById');
		Route::post('updateInfoDieuTri','PhongKham\PhongKhamController@updateInfoDieuTri');
		Route::get('getListPhongKham/{hsbaId}','PhongKham\PhongKhamController@getListPhongKham');
		Route::post('xuTriBenhNhan','PhongKham\PhongKhamController@xuTriBenhNhan');
		Route::get('getIcd10ByCode/{icd10Code}','PhongKham\PhongKhamController@getIcd10ByCode');
		Route::post('saveYLenh','PhongKham\PhongKhamController@saveYLenh');
		Route::get('getLichSuYLenh','PhongKham\PhongKhamController@getLichSuYLenh');
		Route::get('getPddtByIcd10Code/{icd10Code}','PhongKham\PhongKhamController@getPddtByIcd10Code');
		Route::get('getListPhieuYLenh/{id}/{type}','PhongKham\PhongKhamController@getListPhieuYLenh');
		Route::get('getDetailPhieuYLenh/{id}/{type}','PhongKham\PhongKhamController@getDetailPhieuYLenh');		
    });
    
    Route::group(['prefix' => 'danhmuc'], function () {
		Route::get('getListDanhMucDichVu','DanhMuc\DanhMucController@getListDanhMucDichVu');
		Route::get('getDmdvById/{dmdvId}','DanhMuc\DanhMucController@getDmdvById');
    	Route::post('createDanhMucDichVu','DanhMuc\DanhMucController@createDanhMucDichVu');
    	Route::post('updateDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@updateDanhMucDichVu');
    	Route::delete('deleteDanhMucDichVu/{dmdvId}','DanhMuc\DanhMucController@deleteDanhMucDichVu');
    	Route::get('getYLenhByLoaiNhom/{loaiNhom}','DanhMuc\DanhMucController@getYLenhByLoaiNhom');
    	Route::get('getListDanhMucTongHop','DanhMuc\DanhMucController@getListDanhMucTongHop');
    	Route::get('getAllKhoaDanhMucTongHop','DanhMuc\DanhMucController@getAllKhoaDanhMucTongHop');
		Route::get('getDmthById/{dmthId}','DanhMuc\DanhMucController@getDmthById');
    	Route::get('getDanhMucTongHopTheoKhoa/{khoa}','DanhMuc\DanhMucController@getDanhMucTongHopTheoKhoa');
    	Route::post('createDanhMucTongHop','DanhMuc\DanhMucController@createDanhMucTongHop');
    	Route::post('updateDanhMucTongHop/{dmthId}','DanhMuc\DanhMucController@updateDanhMucTongHop');
    	Route::delete('deleteDanhMucTongHop/{dmthId}','DanhMuc\DanhMucController@deleteDanhMucTongHop');
    	Route::get('getListDanhMucTrangThai','DanhMuc\DanhMucController@getListDanhMucTrangThai');
    	Route::get('getListDanhMucTrangThaiByKhoa/{khoa}','DanhMuc\DanhMucController@getListDanhMucTrangThaiByKhoa');
    	Route::get('getDmttById/{dmttId}','DanhMuc\DanhMucController@getDmttById');
    	Route::post('createDanhMucTrangThai','DanhMuc\DanhMucController@createDanhMucTrangThai');
    	Route::post('updateDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@updateDanhMucTrangThai');
    	Route::delete('deleteDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@deleteDanhMucTrangThai');
    	Route::get('getThuocVatTuByLoaiNhom/{loaiNhom}','DanhMuc\DanhMucController@getThuocVatTuByLoaiNhom');
    });
    
    Route::group(['prefix' => 'nguoidung'], function () {
		Route::get('getListNguoiDung','AuthUser\AuthUserController@getListNguoiDung');
 		Route::get('getAuthUsersById/{id}','AuthUser\AuthUserController@getAuthUsersById');
     	Route::post('createAuthUsers','AuthUser\AuthUserController@createAuthUsers');
     	Route::post('updateAuthUsers/{id}','AuthUser\AuthUserController@updateAuthUsers');
     	Route::delete('deleteAuthUsers/{id}','AuthUser\AuthUserController@deleteAuthUsers');
     	Route::get('checkEmail/{email}','AuthUser\AuthUserController@checkEmailbyEmail');
    });
    
    Route::group(['prefix' => 'nhomnguoidung'], function () {
		Route::get('getListAuthGroups','AuthController@getListAuthGroups');
		Route::get('getByListId','AuthController@getAuthGroupsByListId');
		Route::post('createAuthGroups','AuthController@createAuthGroups');
		Route::get('getAuthGroupsById/{id}','AuthController@getAuthGroupsById');
		Route::post('updateAuthGroups/{id}','AuthController@updateAuthGroups');
		Route::get('getTreeListKhoaPhong','AuthController@getTreeListKhoaPhong');
		Route::get('getAuthUsersGroups/{id}/{benhVienId}','AuthController@getAuthGroupsByUsersId');
		Route::get('getListRoles','AuthController@getListRoles');
		Route::get('getRolesByGroupsId/{id}','AuthController@getRolesByGroupsId');
		Route::get('getKhoaPhongByGroupsId/{id}/{benhVienId}','AuthController@getKhoaPhongByGroupsId');
    });     
    
    Route::group(['prefix' => 'thungan'], function () {
		Route::post('createSoThuNgan','ThuNgan\ThuNganController@createSoThuNgan');
// 		Route::post('getThongTinVienPhi','ThuNgan\ThuNganController@getThongTinVienPhi');
        Route::get('getListDichVuByHsbaId/{hsbaId}','ThuNgan\ThuNganController@getListDichVuByHsbaId');
    });
    
    Route::group(['prefix' => 'phieuthu'], function () {
        Route::get('getListSoPhieuThu','PhieuThu\PhieuThuController@getListSoPhieuThu');
        Route::get('getSoPhieuThuById/{id}','PhieuThu\PhieuThuController@getSoPhieuThuById');
        Route::post('createSoPhieuThu','PhieuThu\PhieuThuController@createSoPhieuThu');
        Route::post('updateSoPhieuThu/{id}','PhieuThu\PhieuThuController@updateSoPhieuThu');
    	Route::delete('deleteSoPhieuThu/{id}','PhieuThu\PhieuThuController@deleteSoPhieuThu');
        
        Route::get('getListPhieuThuBySoPhieuThuId/{soPhieuThuId}','PhieuThu\PhieuThuController@getListPhieuThuBySoPhieuThuId');
        Route::get('getListPhieuThuByHsbaId/{hsbaId}','PhieuThu\PhieuThuController@getListPhieuThuByHsbaId');
        Route::post('createPhieuThu','PhieuThu\PhieuThuController@createPhieuThu');
    });
    
    Route::group(['prefix' => 'phacdodieutri'], function () {
		Route::get('getListPhacDoDieuTri','PhacDoDieuTri\PhacDoDieuTriController@getListPhacDoDieuTri');
		Route::get('getPddtById/{pddtId}','PhacDoDieuTri\PhacDoDieuTriController@getPddtById');
		Route::post('savePddt/{pddtId}','PhacDoDieuTri\PhacDoDieuTriController@savePddt');
		Route::get('getPddtByCode/{icd10Code}','PhacDoDieuTri\PhacDoDieuTriController@getPddtByCode');
		Route::post('saveYLenhGiaiTrinh','PhacDoDieuTri\PhacDoDieuTriController@saveYLenhGiaiTrinh');
		Route::post('confirmGiaiTrinh','PhacDoDieuTri\PhacDoDieuTriController@confirmGiaiTrinh');
    });
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');