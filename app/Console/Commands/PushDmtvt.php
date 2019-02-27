<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DanhMucThuocVatTuService;

class PushDmtvt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushDmtvt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push danh muc thuoc vat tu len Elasticsearch';
    
    private $hsbaKhoaPhongRedisRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DanhMucThuocVatTuService $danhMucThuocVatTuService)
    {
        parent::__construct();
        $this->danhMucThuocVatTuService = $danhMucThuocVatTuService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$data = $this->danhMucThuocVatTuService->pushToRedis();
        $this->danhMucThuocVatTuService->pushToElasticSearch();
    }
}
