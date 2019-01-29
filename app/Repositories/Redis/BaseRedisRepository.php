<?php

namespace App\Repositories\Redis;

use Predis\Collection\Iterator;

use App\Models\Redis\BaseModel;

abstract class BaseRedisRepository 
{
    protected $redis = null;
    
    public function init($model,$type,$prefix) {
        $this->setModel($model);
        $this->model->init($type,$prefix);
        $this->redis = $this->model->getClient();
    }
    
    /**
     * Set Model
     * @return mixed
     */
    public function setModel($model){
        $this->model = app()->make(
           $model
        );
        //var_dump($this->model);
    }
    
    /**
     * set item into hash
     * @param $messageAttributes, $messageBody
     * @return mixed
     */
    public function hmset($suffix, array $hashItems) {
        if ($this->model->getType() !== BaseModel::HASH_TYPE) {
            $hashKey = $this->model->getHashPrefix().":".$suffix;
            $this->redis->hmset($hashKey, $hashItems);
        } else {
            throw new \Exception("hmset is not support for this model type");
        }
    }
    
    /**
     * set item string
     * @param $messageAttributes, $messageBody
     * @return mixed
     */
    public function find($suffix) {
        $items = [];
        if ($this->model->getType() !== BaseModel::HASH_TYPE) {
            $match = $suffix?$this->model->getHashPrefix().":".$suffix:$this->model->getHashPrefix();
            //$match = '*hsba_khoa_phong:1*:100';
            echo $match."<br/>";
            foreach(new Iterator\Keyspace($this->redis, $match."*") as $hash)
            {
                //var_dump($this->redis->hscan($hash,1));
                //echo "<br/>";
                $items[] = $this->redis->hscan($hash,1)[1];
            }
            //echo "end<br/>";
        } else {
            throw new \Exception("hscan is not support for this model type");
        }
        //var_dump($items);
        return $items;
        
    }
    
    
}