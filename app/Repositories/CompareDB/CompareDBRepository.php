<?php
namespace App\Repositories\CompareDB;

use DB;
use Config;
use Illuminate\Support\Facades\Schema;
use App\Repositories\BaseRepository;

class CompareDBRepository extends BaseRepository
{
    
    public function getModel()
    {

    }     
    
    private function setConnection($database)
    {
        config(['database.connections.onthefly' => [
            'database' => $database
        ]]);
        return DB::connection('onthefly');
    }    
    
    public function getInfoTable($input)
    {
        // $select = 'select COLUMN_NAME, DATA_TYPE
        //     from INFORMATION_SCHEMA.COLUMNS
        //     where TABLE_NAME='.'hsba';
        // // $connection = $this->setConnection($input['database']);
        // // $result = $connection->select($select); 
        // $result = DB::select($select);
        $columns1= Schema::getColumnListing($input['tableName1']);
        //$columns2= Schema::getColumnListing($input['tableName2']);
        $table1=[];
        foreach($columns1 as $column){
            $dataType = DB::connection()->getDoctrineColumn($input['tableName1'], $column)->getType()->getName();
            $table1[]=[
                'column'=>$column,
                'data_type'=>$dataType
                ];
        }
        $result['table1']=$table1;

        return $result;
    }
}