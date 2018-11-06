<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class AuthUsersRepository extends BaseRepository
{

     public function getIdbyEmail($email)
    {
        $data = DB::table('auth_users')
                ->where('email',$email)
                ->first();
        if($data)
            return $data;
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
    
}