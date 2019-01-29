<?php
namespace App\Models\Redis;

use Illuminate\Support\Facades\Redis;

abstract class BaseModel
{
    const STRING_TYPE = 1;
    const SET_TYPE = 2;
    const HASH_TYPE = 3;
    
    protected $type = null;
    
    protected $hashPrefix = null;
    protected $stringPrefix = null;
    
    protected $client = null;
    
    public $item = null;
    
    public function  __construct($item = null) {
        if ($item) {
            $this->item = $item;
        }
    }
    
    public function init(int $type, $prefix) {
        $this->setClient();
        if ($type == self::HASH_TYPE) {
            $this->setHashPrefix($prefix);
        }
        if ($type == self::STRING_TYPE) {
            $this->setStringPrefix($prefix);
        }
    }
    
    /**
     * Set Type
     * @return mixed
     */
    private function setType(int $type){
        $this->type = $type;
    }
    
    /**
     * Get Type
     * @return mixed
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     * Set Hash prefix
     * @return mixed
     */
    private function setHashPrefix($prefix){
        $this->hashPrefix = $prefix;
    }
    
    /**
     * Get Hash prefix
     * @return mixed
     */
    public function getHashPrefix(){
        return $this->hashPrefix;
    }
    
    /**
     * Set String Key
     * @return mixed
     */
    private function setStringPrefix($prefix){
        $this->stringPrefix = $prefix;
    }
    
    /**
     * Get String Key
     * @return mixed
     */
    public function getStringPrefix(){
        return $this->stringPrefix;
    }
    
    /**
     * Set client
     * @return mixed
     */
    private function setClient(){
        $redis = Redis::connection()->client();
        $this->client = $redis;
    }
    
    /**
     * Get client
     * @return mixed
     */
    public function getClient(){
        return $this->client;
    }
    
}
