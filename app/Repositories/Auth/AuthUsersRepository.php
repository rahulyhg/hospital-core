<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthUsers;
use Carbon\Carbon;

class AuthUsersRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthUsers::class;
    }    

     public function getIdbyEmail($email)
    {
        $data = DB::table('auth_users')
                ->where('email',$email)
                ->first();
        if($data)
            return $data;
        else 
            return null;
    }
    
    public function getUserNameByEmail($email)
    {
        $data = DB::table('auth_users')
                ->where('email',$email)
                ->first();
        if($data)
            return $data;
    }
    
    public function getUserById($authUsersId)
    {
        $data = DB::table('auth_users')
                ->where('id', $authUsersId)
                ->first();
        if($data)
            return true;
        else
            return false;
    }
    
    public function getListNguoiDung($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'fullname',
            'email',
            'khoa',
            'chuc_vu',
            'created_at',
            'updated_at',
            'userstatus'
        ];
        
        $query = DB::table('auth_users');
        
        if($keyWords!=""){
           $query->where('fullname', 'like', '%' . $keyWords . '%')
                 ->orWhere('email', 'like', '%' . $keyWords . '%');
        }
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'desc')
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
    
    public function getAuthUsersById($id)
    {
        $result = DB::table('auth_users')->where('id', $id)->first(); 
        return $result;
    }
    
    public function createAuthUsers(array $input)
    {
        $unique=DB::table('auth_users')->where('email',$input['email'])->first();
        if(!$unique)
        {
            if($input['userstatus']==true)
                $input['userstatus']=1;
            else
                $input['userstatus']=0;
            $input['password']=bcrypt($input['password']);    
            $input['created_at']=Carbon::now()->toDateTimeString();
            $input['updated_at']=Carbon::now()->toDateTimeString();
            $id = AuthUsers::create($input)->id;
            return $id;
        }
    }
    public function deleteAuthUsers($id)
    {
        AuthUsers::destroy($id);
    }
    
    public function checkEmailbyEmail($email)
    {
        $data = DB::table('auth_users')
                ->where('email', $email)
                ->first();
        return $data;
    }
    
    public function updateAuthUsers($id, array $input)
    {
        if($input['userstatus']==true)
            $input['userstatus']=1;
        else
            $input['userstatus']=0; 
        $update = AuthUsers::findOrFail($id);
		$update->update($input);
    }    
}