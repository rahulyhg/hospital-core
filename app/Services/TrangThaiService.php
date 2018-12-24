<?php
namespace App\Services;

use App\Repositories\TrangThaiRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;


class TrangThaiService
{
    public function __construct(TrangThaiRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function changeToState($tableModel, $attributes) {
        $this->repo->changeToState($tableModel, $attributes);
    } 
}