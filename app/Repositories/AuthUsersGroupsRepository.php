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
    
}