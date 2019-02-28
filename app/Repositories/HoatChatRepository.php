<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\HoatChat;

class HoatChatRepository extends BaseRepositoryV2
{
    public function getModel()
    {
      return HoatChat::class;
    }    

    public function getById($id)
    {
      $data = $this->model
              ->where('id', $id)
              ->first();
      return $data;
    }     
}