<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HanhChinh;

class HanhChinhRepository extends BaseRepositoryV2
{
  public function getModel()
  {
      return HanhChinh::class;
  }
  
  public function getDataTinh($value)
  {
      $data = $this->model
              ->whereRaw("upper(hanh_chinh.ten_tinh) like '%$value%'")
              ->get();
      $array = json_decode($data, true);
    
      return collect($array)->first();  
  }

  public function getDataHuyen($huyen_matinh, $ten_huyen)
  {
      $data = $this->model
              ->where('hanh_chinh.huyen_matinh', '=', $huyen_matinh)
              ->whereRaw("upper(hanh_chinh.ten_huyen) like '%$ten_huyen%'")
              ->get();
      $array = json_decode($data, true);
    
      return collect($array)->first(); 
  }
  
  public function getDataXa($xa_matinh, $xa_mahuyen, $ten_xa)
  {
      $where = [
              ['hanh_chinh.xa_matinh', '=', $xa_matinh],
              ['hanh_chinh.xa_mahuyen', '=', $xa_mahuyen],
          ];
      $data = $this->model
              ->where($where)
              ->whereRaw("upper(hanh_chinh.ten_xa) like '%$ten_xa%'")
              ->get();
      $array = json_decode($data, true);
    
      return collect($array)->first();  
  }  
  
  public function getListTinh()
  {
      $tinh = $this->model
              ->where('ma_tinh','<>',0)
              ->get();
      return $tinh;    
  }
  
  public function getListHuyen($maTinh)
  {
      $huyen = $this->model
              ->where('ma_huyen','<>',0)
              ->where('huyen_matinh','=',$maTinh)
              ->get();
      return $huyen;    
  }
  
  public function getListXa($maHuyen,$maTinh)
  {
      $huyen = $this->model
              ->where('ma_xa','<>',0)
              ->where('xa_mahuyen','=',$maHuyen)
              ->where('xa_matinh','=',$maTinh)
              ->get();
      return $huyen;    
  }
  
  public function getThxByKey($thxKey)
  {
      $key = explode(" ",$thxKey);
      $result = array();
      if(count($key)==1){
          $dataTinh = $this->model
              ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
              ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
          if($dataTinh){
              foreach($dataTinh as $itemTinh){
                  $dataHuyen = $this->model
                      ->where('huyen_matinh',$itemTinh->ma_tinh)
                      ->where('ma_huyen','<>',0)
                      ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                  if($dataHuyen){
                      foreach($dataHuyen as $itemHuyen){
                          $dataXa = $this->model
                              ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                              ->where('xa_matinh',$itemTinh->ma_tinh)
                              ->get(['ten_xa','ma_xa','kyhieu_xa']);
                          if($dataXa){
                              foreach($dataXa as $itemXa){
                                  $result[] = [
                                      'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                      'ma_tinh'=>$itemTinh->ma_tinh,
                                      'ma_huyen'=>$itemHuyen->ma_huyen,
                                      'ma_xa'=>$itemXa->ma_xa,
                                      'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                      'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                  ];
                              }
                          }
                      }
                  }
              }
          }
      }
      if(count($key)==2){
          $dataTinh = $this->model
                  ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
                  ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
          if($dataTinh){
              foreach($dataTinh as $itemTinh){
                  $dataHuyen = $this->model
                      ->where('huyen_matinh',$itemTinh->ma_tinh)
                      ->where('ma_huyen','<>',0)
                      ->where('kyhieu_huyen','like',strtoupper($key[1]).'%')
                      ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                  if($dataHuyen){
                      foreach($dataHuyen as $itemHuyen){
                          $dataXa = $this->model
                              ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                              ->where('xa_matinh',$itemTinh->ma_tinh)
                              ->where('ma_xa','<>',0)
                              ->get(['ten_xa','kyhieu_xa','ma_xa']);
                          if($dataXa){
                              foreach($dataXa as $itemXa){
                                  $result[] = [
                                      'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                      'ma_tinh'=>$itemTinh->ma_tinh,
                                      'ma_huyen'=>$itemHuyen->ma_huyen,
                                      'ma_xa'=>$itemXa->ma_xa,
                                      'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                      'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                  ];                                    
                              }
                          }
                      }
                  }
              }
          }
      }
      if(count($key)==3){
          $dataTinh = $this->model
                  ->where('kyhieu_tinh','like',strtoupper($key[0]).'%')
                  ->get(['ma_tinh','ten_tinh','kyhieu_tinh']);
          if($dataTinh){
              foreach($dataTinh as $itemTinh){
                  $dataHuyen = $this->model
                      ->where('huyen_matinh',$itemTinh->ma_tinh)
                      ->where('ma_huyen','<>',0)
                      ->where('kyhieu_huyen','like',strtoupper($key[1]).'%')
                      ->get(['ma_huyen','ten_huyen','huyen_matinh','kyhieu_huyen']);
                  if($dataHuyen){
                      foreach($dataHuyen as $itemHuyen){
                          $dataXa = $this->model
                              ->where('kyhieu_xa','like',strtoupper($key[2]).'%')
                              ->where('xa_mahuyen',$itemHuyen->ma_huyen)
                              ->where('xa_matinh',$itemTinh->ma_tinh)
                              ->where('ma_xa','<>',0)
                              ->get(['ten_xa','kyhieu_xa','ma_xa']);
                          if($dataXa){
                              foreach($dataXa as $itemXa){
                                  $result[] = [
                                      'dia_chi'=>$itemTinh->ten_tinh.' - '.$itemHuyen->ten_huyen.' - '.$itemXa->ten_xa,
                                      'ma_tinh'=>$itemTinh->ma_tinh,
                                      'ma_huyen'=>$itemHuyen->ma_huyen,
                                      'ma_xa'=>$itemXa->ma_xa,
                                      'thx_code'=>$itemTinh->ma_tinh.' '.$itemHuyen->ma_huyen.' '.$itemXa->ma_xa,
                                      'thx_ky_hieu'=>$itemTinh->kyhieu_tinh.' '.$itemHuyen->kyhieu_huyen.' '.$itemXa->kyhieu_xa
                                  ];                                    
                              }
                          }
                      }
                  }
              }
          }
      }
      return $result;
  }      
    
}