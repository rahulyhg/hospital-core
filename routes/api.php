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
        Route::get('getKhoaByBenhVienId/{benhVienId}','UserSetting\UserSettingController@getKhoaByBenhVienId');
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
    	Route::get('hinhThucChuyen','DangKyKhamBenh\DangKyKhamBenhController@getListHinhThucChuyen');
    	Route::get('tuyen','DangKyKhamBenh\DangKyKhamBenhController@getListTuyen');
    	Route::get('lyDoChuyen','DangKyKhamBenh\DangKyKhamBenhController@getListLyDoChuyen');
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
		Route::get('getLichSuThuocVatTu','PhongKham\PhongKhamController@getLichSuThuocVatTu');
		Route::get('getPddtByIcd10Code/{icd10Code}','PhongKham\PhongKhamController@getPddtByIcd10Code');
		Route::get('getListPhieuYLenh/{id}/{type}','PhongKham\PhongKhamController@getListPhieuYLenh');
		Route::get('getDetailPhieuYLenh/{id}/{type}','PhongKham\PhongKhamController@getDetailPhieuYLenh');	
		Route::post('updateHsbaPhongKham/{hsbaKhoaPhongId}','PhongKham\PhongKhamController@updateHsbaPhongKham');
		Route::get('getDetailHsbaPhongKham/{hsbaId}/{phongId}','PhongKham\PhongKhamController@getDetailHsbaPhongKham');
        Route::get('countItemYLenh/{hsbaId}','PhongKham\PhongKhamController@countItemYLenh');
        Route::get('countItemThuocVatTu/{hsbaId}','PhongKham\PhongKhamController@countItemThuocVatTu');
        Route::get('searchIcd10Code/{icd10Code}','PhongKham\PhongKhamController@searchIcd10Code');
        Route::get('searchIcd10Text/{icd10Text}','PhongKham\PhongKhamController@searchIcd10Text');
        Route::get('getListHsbaPhongKham/{hsbaId}','PhongKham\PhongKhamController@getListHsbaPhongKham');
        Route::get('getAllCanLamSang/{hsbaId}','PhongKham\PhongKhamController@getAllCanLamSang');
        Route::get('searchListIcd10ByCode/{icd10Code}','PhongKham\PhongKhamController@searchListIcd10ByCode');
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
    	Route::get('getAllKhoaDanhMucTrangThai','DanhMuc\DanhMucController@getAllKhoaDanhMucTrangThai');
    	Route::get('getDanhMucTrangThaiTheoKhoa/{khoa}','DanhMuc\DanhMucController@getDanhMucTrangThaiTheoKhoa');
    	Route::get('getDmttById/{dmttId}','DanhMuc\DanhMucController@getDmttById');
    	Route::post('createDanhMucTrangThai','DanhMuc\DanhMucController@createDanhMucTrangThai');
    	Route::post('updateDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@updateDanhMucTrangThai');
    	Route::delete('deleteDanhMucTrangThai/{dmttId}','DanhMuc\DanhMucController@deleteDanhMucTrangThai');
    	Route::get('getThuocVatTuByLoaiNhom/{loaiNhom}','DanhMuc\DanhMucController@getThuocVatTuByLoaiNhom');
    	Route::get('getThuocVatTuByCode/{maNhom}/{loaiNhom}','DanhMuc\DanhMucController@getThuocVatTuByCode');
    	Route::get('getListNhomDanhMuc','DanhMuc\DanhMucController@getListNhomDanhMuc');
    	Route::get('getNhomDmById/{id}','DanhMuc\DanhMucController@getNhomDmById');
    	Route::post('createNhomDanhMuc','DanhMuc\DanhMucController@createNhomDanhMuc');
    	Route::post('updateNhomDanhMuc/{id}','DanhMuc\DanhMucController@updateNhomDanhMuc');
    });
    
    Route::group(['prefix' => 'nguoidung'], function () {
		Route::get('getListNguoiDung','AuthUser\AuthUserController@getListNguoiDung');
 		Route::get('getAuthUsersById/{id}','AuthUser\AuthUserController@getAuthUsersById');
     	Route::post('createAuthUsers','AuthUser\AuthUserController@createAuthUsers');
     	Route::post('updateAuthUsers/{id}','AuthUser\AuthUserController@updateAuthUsers');
     	Route::delete('deleteAuthUsers/{id}','AuthUser\AuthUserController@deleteAuthUsers');
     	Route::get('checkEmail/{email}','AuthUser\AuthUserController@checkEmailbyEmail');
     	Route::post('resetPassword','AuthUser\AuthUserController@resetPasswordByUserId');
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
        Route::get('getListIcd10','PhacDoDieuTri\PhacDoDieuTriController@getListIcd10');
        Route::get('searchIcd10/{keyword}','PhacDoDieuTri\PhacDoDieuTriController@searchIcd10');
        Route::post('createPddt','PhacDoDieuTri\PhacDoDieuTriController@createPddt');
        Route::get('getPddtByIcd10Id/{icd10Id}','PhacDoDieuTri\PhacDoDieuTriController@getPddtByIcd10Id');
        Route::get('getPddtById/{pddtId}','PhacDoDieuTri\PhacDoDieuTriController@getPddtById');
        Route::post('updatePddt/{pddtId}','PhacDoDieuTri\PhacDoDieuTriController@updatePddt');
// 		Route::get('getListPhacDoDieuTri','PhacDoDieuTri\PhacDoDieuTriController@getListPhacDoDieuTri');
// 		Route::get('getPddtByCode/{icd10Code}','PhacDoDieuTri\PhacDoDieuTriController@getPddtByCode');
		Route::post('saveYLenhGiaiTrinh','PhacDoDieuTri\PhacDoDieuTriController@saveYLenhGiaiTrinh');
		Route::post('confirmGiaiTrinh','PhacDoDieuTri\PhacDoDieuTriController@confirmGiaiTrinh');
    });
    
    Route::group(['prefix' => 'hsbakp'], function () {
        Route::get('list/{benhVienId}/khoakhambenh','Hsba\HsbaKhoaPhongController@getListKhoaKhamBenh');
    });
    
    Route::group(['prefix' => 'hsba'], function () {
        Route::get('list/{benhVienId}/thungan','Hsba\HsbaController@getListThuNgan');
    });
    
    Route::group(['prefix' => 'thanhtoanvienphi'], function () {
        Route::get('getListVienPhiByHsbaId/{hsbaId}','ThanhToanVienPhi\ThanhToanVienPhiController@getListVienPhiByHsbaId');
        Route::get('getListYLenhByVienPhiId/{vienPhiId}/{keyWords}','ThanhToanVienPhi\ThanhToanVienPhiController@getListYLenhByVienPhiId');
        Route::post('updateYLenh/{yLenhId}','ThanhToanVienPhi\ThanhToanVienPhiController@updateYLenhById');
        Route::post('createVienPhi','ThanhToanVienPhi\ThanhToanVienPhiController@createVienPhi');
    });
    
    Route::group(['prefix' => 'kho'], function () {
		Route::get('getListKho','Kho\KhoController@getListKho');
		Route::post('createKho','Kho\KhoController@createKho');
     	Route::post('updateKho/{id}','Kho\KhoController@updateKho');
     	Route::delete('deleteKho/{id}','Kho\KhoController@deleteKho');
 		Route::get('getKhoById/{id}','Kho\KhoController@getKhoById');
 		
 		//Route::get('getListThuocVatTu/{keyWords}','Kho\KhoController@getListThuocVatTu');
 		Route::get('getAllThuocVatTu','Kho\KhoController@getAllThuocVatTu');
 		Route::get('searchThuocVatTuByKeywords/{keyWords}','Kho\KhoController@searchThuocVatTuByKeywords');
    });
    
    Route::group(['prefix' => 'phieunhapkho'], function () {
		Route::post('createPhieuNhapKho','PhieuNhapKho\PhieuNhapKhoController@createPhieuNhapKho');
    });    
    
    Route::group(['prefix' => 'phongbenh'], function () {
		Route::get('getListPhongBenh','NoiTru\PhongBenhController@getListPhongBenh');
		Route::post('createPhongBenh','NoiTru\PhongBenhController@createPhongBenh');
     	Route::post('updatePhongBenh/{id}','NoiTru\PhongBenhController@updatePhongBenh');
     	Route::delete('deletePhongBenh/{id}','NoiTru\PhongBenhController@deletePhongBenh');
 		Route::get('getPhongBenhById/{id}','NoiTru\PhongBenhController@getPhongBenhById');
    });    
    
    Route::group(['prefix' => 'nhacungcap'], function () {
		Route::get('getListNhaCungCap','NhaCungCap\NhaCungCapController@getListNhaCungCap');
		Route::post('createNhaCungCap','NhaCungCap\NhaCungCapController@createNhaCungCap');
     	Route::post('updateNhaCungCap/{id}','NhaCungCap\NhaCungCapController@updateNhaCungCap');
     	Route::delete('deleteNhaCungCap/{id}','NhaCungCap\NhaCungCapController@deleteNhaCungCap');
 		Route::get('getNhaCungCapById/{id}','NhaCungCap\NhaCungCapController@getNhaCungCapById');
    });    
    
    Route::group(['prefix' => 'auth', 'middleware' => 'jwt.auth'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');