<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\TheKho;

class TheKhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return TheKho::class;
    }  
    
    public function createTheKho(array $input)
    {
        $find = $this->model
                     ->where('danh_muc_thuoc_vat_tu_id',$input['danh_muc_thuoc_vat_tu_id'])
                     ->orderBy('ma_con','DESC')
                     ->first();
        if($find) {
            $explode = explode('.',$find['ma_con']);
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.'.(intval($explode[1])+1);
        }
        else {
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.1';
        }
        
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getTonKhaDungByThuocVatTuId($id)
    {
        $column = [
            'sl_kha_dung',
            'sl_ton_kho_chan',
            'sl_ton_kho_le'
            ];
        $data = $this->model
            ->where('danh_muc_thuoc_vat_tu_id',$id)
            ->select(DB::raw('(sl_ton_kho_chan+sl_ton_kho_le) AS sl_ton_kho'),'sl_kha_dung')
            ->get();
        $slKhaDung = 0;
        $slTonKho = 0;
        $result=[];
        foreach($data as $item) {
            $slKhaDung+=$item->sl_kha_dung;
            $slTonKho+=$item->sl_ton_kho;
        }
        $result[]=[
            'so_luong_ton' => $slTonKho,
            'so_luong_kha_dung' => $slKhaDung
            ];
        return $result[0];
    }
    
    public function updateTheKho(array $input)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id','=',$input['danh_muc_thuoc_vat_tu_id']],
            ['kho_id','=',$input['kho_id']]
            ];
        $find = $this->model
                     ->where($where)
                     ->orderBy('ma_con','DESC')
                     ->first();
        if($find) {
            $newKhaDung = $find['sl_kha_dung']-$input['so_luong'];
            $this->model->where('id',$find['id'])->update(['sl_kha_dung' => $newKhaDung]);
            return $find['id'];
        }
        else
            return 0;
    }    
}