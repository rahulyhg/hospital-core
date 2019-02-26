<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NhomDanhMuc;

class NhomDanhMucRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return NhomDanhMuc::class;
    }    

    public function getListNhomDanhMuc()
    {
        $result = $this->model->orderBy('id')->get();
        if($result) {
            list($parent, $children) = $result->partition(function($item) {
                return $item->parent_id == 0;
            });
            
            $data = $parent->each(function($itemParent, $keyParent) use ($children) {
                $arrayChildren = $children;
                $arrayChildrenLv1 = $children->filter(function($itemChildren, $keyChildren) use ($itemParent, $arrayChildren) {
                    if($itemChildren->parent_id == $itemParent->id) {
                        $arrayChildrenLv2 = $arrayChildren->filter(function($itemChildrenLv2, $keyChildrenLv2) use ($itemChildren) {
                            if($itemChildrenLv2->parent_id == $itemChildren->id) {
                                return $itemChildrenLv2;
                            }
                        })->values()->all();
                        $itemChildren['children'] = $arrayChildrenLv2;
                        return $itemChildren;
                    }
                })->values()->all();
                $itemParent['children'] = $arrayChildrenLv1;
            })->values()->all();
            
            return $data;   
        } else {
            return [];
        }
    }
    
    public function createNhomDanhMuc(array $input)
    {
        $id = $this->create($input)->id;
        return $id;
    }
    
    public function updateNhomDanhMuc($id, array $input)
    {
        $nhomDanhMuc = $this->model->findOrFail($id);
		$nhomDanhMuc->update($input);
    }
    
    public function getNhomDmById($id)
    {
        $data = $this->model->where('id', $id)->first(); 
        return $data;
    }
}