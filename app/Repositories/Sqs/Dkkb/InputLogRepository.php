<?php
namespace App\Repositories\Sqs\Dkkb;

use App\Repositories\Sqs\BaseSQSRepository;
use App\Models\Sqs\DangKyKhamBenh as DangKyKhamBenhMessage;

class InputLogRepository extends BaseSQSRepository
{
    public function __construct() {
        $this->init(DangKyKhamBenhMessage::class,'dkkb-to-log');
    }
}