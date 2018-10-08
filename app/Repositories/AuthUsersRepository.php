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
            return $data->id;    
    }
    
}