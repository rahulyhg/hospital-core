<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthRoles;
use Carbon\Carbon;

class AuthRolesRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthRoles::class;
    } 

    public function getListRoles($limit = 100, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'name',
            'description'
        ];
        
        $query = $this->model;
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
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
    
}