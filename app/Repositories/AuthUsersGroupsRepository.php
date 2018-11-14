<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class AuthUsersGroupsRepository extends BaseRepository
{

     public function getIdGroupbyId($id)
    {
        $dataSet = DB::table('auth_users_groups')->where('user_id',$id)->get();
        if($dataSet)
        {
            $result= array();
            foreach($dataSet as $dataset)
            {
                $result[] = $dataset->group_id;
            }
    
            //$output = new \Symfony\Component\Console\Output\ConsoleOutput();
            //$output->writeln("<info><pre>".dd($data)."</pre></info>");
            return $result;
        }
    }

     public function getKhoaPhongByUserId($id)
    {
        $dataSet = DB::table('auth_users_groups')
                    ->where([
                        ['user_id','=',$id],
                        ['khoa_id','<>',null],
                        ['phong_id','<>',null],
                        ])
                    ->get(['khoa_id','phong_id']);
        

        $phongId = $dataSet->implode('phong_id', ',');
        $khoaId = $dataSet->implode('khoa_id', ',');
         
        $phongIdArray = explode(",",$phongId);
        $khoaIdArray = explode(",",$khoaId);
        
        $result = DB::table('phong')
                ->whereIn('id',$phongIdArray)
                ->whereIn('khoa_id',$khoaIdArray)
                ->get(['id','khoa_id','ten_phong']);
        return $result;
        
    }    
    
    
}