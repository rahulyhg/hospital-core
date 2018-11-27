<?php
namespace App\Repositories\Auth;
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
        
        if(count($dataSet)>0)
        {
            $phongId = $dataSet->implode('phong_id', ',');
            $khoaId = $dataSet->implode('khoa_id', ',');
            $phongIdArray = explode(",",$phongId);
            $khoaIdArray = explode(",",$khoaId);
            $column=[
                'phong.id',
                'phong.khoa_id',
                'phong.ten_phong',
                'khoa.ten_khoa'
                ];
            $result = DB::table('phong')
                    ->whereIn('phong.id',$phongIdArray)
                    ->whereIn('phong.khoa_id',$khoaIdArray)
                    ->leftJoin('khoa','khoa.id','=','phong.khoa_id')
                    ->get($column);
            return $result;
        }
        
    }    
    
    
}