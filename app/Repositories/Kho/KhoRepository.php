<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Kho;
use Carbon\Carbon;
class KhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
      return Kho::class;
    }    
    
    public function getListKho($limit = 100, $page = 1, $keyWords ='', $benhVienId)
    {
      $offset = ($page - 1) * $limit;

      $model = $this->model->where('benh_vien_id','=',$benhVienId);
      
      if($keyWords!=""){
        $model->whereRaw('LOWER(ten_kho) LIKE ? ',['%'.strtolower($keyWords).'%'])
              ->orWhereRaw('LOWER(ky_hieu) LIKE ? ',['%'.strtolower($keyWords).'%']);
      }
          
      $totalRecord = $model->count();
      if($totalRecord) {
          $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
          $data = $model->orderBy('id', 'desc')
                      ->offset($offset)
                      ->limit($limit)
                      ->get();
      } else {
          $totalPage = 0;
          $data = [];
          $page = 0;
          $totalRecord = 0;
      }
          
      $result = [
          'data'          => $data,
          'page'          => $page,
          'totalPage'     => $totalPage,
          'totalRecord'   => $totalRecord
      ];
      
      return $result;
    }
    
    public function createKho(array $input)
    {
      $input['trang_thai']=$input['trang_thai']==true?1:0;
      $input['duoc_ban']=$input['duoc_ban']==true?1:0;
      $input['nhap_tu_ncc']=$input['nhap_tu_ncc']==true?1:0;
      $input['tu_truc']=$input['tu_truc']==true?1:0;
          
      $stt = $this->model->orderBy('stt','DESC')->first();
      
      $input['stt']=$stt?$stt['stt']+1:1;
          
      $id = $this->model->create($input)->id;
      return $id;
    }
    
    public function updateKho($id, array $input)
    {
      $input['trang_thai']=$input['trang_thai']==true?1:0;
      $input['duoc_ban']=$input['duoc_ban']==true?1:0;
      $input['nhap_tu_ncc']=$input['nhap_tu_ncc']==true?1:0;
      $input['tu_truc']=$input['tu_truc']==true?1:0;
      $input['phong_duoc_nhin_thay']=!empty($input['phong_duoc_nhin_thay'])?json_encode($input['phong_duoc_nhin_thay']):null;
      $find = $this->model->findOrFail($id);
	    $find->update($input);
    }
    
    public function deleteKho($id)
    {
      $this->model->destroy($id);
    }
    
    public function getKhoById($id)
    {
      $data = $this->model
              ->where('id', $id)
              ->first();
      return $data;
    }     
}