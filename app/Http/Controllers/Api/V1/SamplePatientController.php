<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\SamplePatientService;
use Illuminate\Support\Facades\Redis;
use Predis\Client;
use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use App\Repositories\Sqs\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongSqsRepository;
use App\Repositories\Redis\Hsba\HsbaKhoaPhongRepository as HsbaKhoaPhongRedisRepository;
use Carbon\Carbon;

class SamplePatientController extends APIController
{
    /**
     * __construct.
     *
     * @param SamplePatientService $service
     */
    public function __construct(SamplePatientService $service, HsbaKhoaPhongSqsRepository $sqsRepo, HsbaKhoaPhongRedisRepository $redisRepo)
    {
        $this->service = $service;
        $this->sqsRepo = $sqsRepo;
        $this->redisRepo = $redisRepo;
        $this->redisRepo->_init('today:hsba_khoa_phong');
    }
    
    /**
     * Return the SamplePatient.
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $list =  $this->redisRepo->getList(100, 1, '', '');
        var_dump($list);
        echo "<hr/>";
        //return 200;
        $messageAttributes = [
            'benh_vien_id' => ['DataType' => "Number",
                                'StringValue' => "1"
                            ],
            'khoa_id' => ['DataType' => "Number",
                                'StringValue' => "10"
                            ],
            'phong_id' => ['DataType' => "Number",
                                'StringValue' => "100"
                            ]
        ];
        
        $messageBody = [
            'benh_vien_id' => 1,
            'hsba_id' => 1001, 
            'hsba_khoa_phong_id' => 10010, 
            'ten_benh_nhan' => 'NNVN', 
            'nam_sinh' => 1987, 
            'ms_bhyt' => 'BBBBAAAXXXzzz', 
            'trang_thai_hsba' => 1,
            'ngay_tao' => '2018-03-14', 
            'ngay_ra_vien' => '2018-05-14', 
            'thoi_gian_vao_vien' => '2018-11-18 15:29:26', 
            'thoi_gian_ra_vien' => '2018-11-19 15:29:26',
            'trang_thai_cls' => 1, 
            'ten_trang_thai_cls' => 'TEN_CLS', 
            'trang_thai' => 1, 
            'ten_trang_thai' => 'TRỐN TRẠI'
        ];
        
        try {
            // Push
            
            $this->sqsRepo->push(
                $messageAttributes,$messageBody
            );
            
            // Pop
            
            $messageObjects = $this->sqsRepo->pop(8);
             
            // foreach ($messageObjects as $k => $messageObject) {
                
                
            //     $messageBody = $messageObject->getBody();
            //     //echo "<hr/><br/>";
            //     //var_dump($messageObject->message['Body']);
            //     //var_dump($messageBody);
            //     //continue;
            //     $benhVienId = $messageBody['benh_vien_id'];
            //     $khoaId = 10;
            //     $phongId= 100;
                
                
            //     $hsbaKPId = $messageBody['hsba_khoa_phong_id'];
            //     $thoiGianVaoVienObject = Carbon::createFromFormat('Y-m-d H:i:s', $messageBody['thoi_gian_vao_vien']);
            //     $ngayVaoVien = $thoiGianVaoVienObject->format('Y-m-d');
            //     $suffix = $benhVienId.':'.$khoaId.':'.$phongId.':'.$ngayVaoVien.":".$hsbaKPId;
            //     $this->redisRepo->hmset($suffix,$messageBody);
                 
            // }
            
            // echo "<pre>";
            // var_dump ($messages);
            // echo "</pre>";
            
        } catch ( \Exception $ex) {
            echo $ex->getMessage();
        }
        
        
        /*
        $queueConfig = config('queue.connections.sqs');
        return var_dump($queueConfig);
        $client = new SqsClient([
            //'profile' => 'default',
            'region' => $queueConfig['region'],
            'version' => $queueConfig['version'],
            'credentials' => [
                'key' => $queueConfig['key'],
                'secret' => $queueConfig['secret'],
            ]
        ]);
        
        $params = [
            'DelaySeconds' => 10,
            'MessageAttributes' => [
            ],
            'MessageBody' => "10101",
            'QueueUrl' => $queueConfig['prefix'].'/'.'add-patient-hsba-to-cache-list'
        ];
        
        try {
            $result = $client->sendMessage($params);
            var_dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
                
        */
        //Redis::set('name', 'GS');
        $name = Redis::get('naming');
        return $name;
        //$data = $this->service->getDataPatient($request);
        
        //return $data;
    }
    
    public function addToQueue(Request $request) {
        
        $queueParams = $request->only(
            'benh_vien_id','hsba_id','hsba_khoa_phong_id', 'ten_benh_nhan', 'nam_sinh', 'ms_bhyt', 'trang_thai_hsba',
            'ngay_tao', 'ngay_ra_vien', 'thoi_gian_vao_vien', 'thoi_gian_ra_vien',
            'trang_thai_cls', 'ten_trang_thai_cls', 'trang_thai', 'ten_trang_thai'
        );
        
        $messageAttributes = [
            'benh_vien_id' => ['DataType' => "Number",
                                'StringValue' => $queueParams['benh_vien_id']
                            ],
            'khoa_id' => ['DataType' => "Number",
                                'StringValue' => $queueParams['khoa_id']
                            ],
            'phong_id' => ['DataType' => "Number",
                                'StringValue' => $queueParams['phong_id']
                            ]
        ];
        
        $messageBody = [
            'benh_vien_id' => $queueParams['benh_vien_id'],
            'hsba_id' => $queueParams['hsba_id'], 
            'hsba_khoa_phong_id' => $queueParams['hsba_khoa_phong_id'], 
            'ten_benh_nhan' => $queueParams['ten_benh_nhan'], 
            'nam_sinh' => $queueParams['nam_sinh'], 
            'ms_bhyt' => $queueParams['ms_bhyt'], 
            'trang_thai_hsba' => $queueParams['trang_thai_hsba'],
            'ngay_tao' => $queueParams['ngay_tao'], 
            'ngay_ra_vien' => $queueParams['ngay_ra_vien'], 
            'thoi_gian_vao_vien' => $queueParams['thoi_gian_vao_vien'], 
            'thoi_gian_ra_vien' => $queueParams['thoi_gian_ra_vien'],
            'trang_thai_cls' => $queueParams['trang_thai_cls'], 
            'ten_trang_thai_cls' => $queueParams['ten_trang_thai_cls'], 
            'trang_thai' => $queueParams['trang_thai'], 
            'ten_trang_thai' => $queueParams['ten_trang_thai']
        ];
        
        try {
            // Push
            
            $this->sqsRepo->push(
                $messageAttributes,$messageBody
            );
            
            
            
        } catch ( \Exception $ex) {
            echo $ex->getMessage();
        }
        
    }
    
    public function getFromQueue() {
        // Pop
            
        $messageObjects = $this->sqsRepo->pop(10);
         
        foreach ($messageObjects as $k => $messageObject) {
            
            
            $messageBody = $messageObject->getBody();
            //echo "<hr/><br/>";
            //var_dump($messageObject->message['Body']);
            //var_dump($messageBody);
            //continue;
            $benhVienId = $messageBody['benh_vien_id'];
            $khoaId = 10;
            $phongId= 100;
            
            
            $hsbaKPId = $messageBody['hsba_khoa_phong_id'];
            $thoiGianVaoVienObject = Carbon::createFromFormat('Y-m-d H:i:s', $messageBody['thoi_gian_vao_vien']);
            $ngayVaoVien = $thoiGianVaoVienObject->format('Y-m-d');
            
            $suffix = $benhVienId.':'.$khoaId.':'.$phongId.':'.$ngayVaoVien.":".$hsbaKPId;
            $this->redisRepo->hmset($suffix,$messageBody);
             
        }
        
        echo "<pre>";
        var_dump ($messages);
        echo "</pre>";
    }
    
    
    public function addToCache() {
        
    }
    
    
    
    /**
     * Return the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $patient = $this->service->showPatient($id);
        
        return $patient;
    }
    
    /**
     * Creates the Resource for SamplePatient.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $patient = $this->service->makePatient($request);
        
        return $patient;
    }
    
    /**
     * Update SamplePatient.
     *
     * @param Request           $request
     * @param int               $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $patient = $this->service->updatePatient($request, $id);
        
        return $patient;
    }
    
    /**
     * Delete SamplePatient.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $message = $this->service->deletePatient($id);
        
        return $message;
    }
    
    
    
}