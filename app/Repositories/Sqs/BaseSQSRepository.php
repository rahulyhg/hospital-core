<?php

namespace App\Repositories\Sqs;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

abstract class BaseSQSRepository implements RepositoryInterface
{
    
    const DEFAULT_NUMBER_OF_MESSAGES = 1;
    
    public function init($model,$uri) {
        $this->setModel($model);
        $this->model->init($uri);
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
     * push item to queue
     * @param $messageAttributes, $messageBody
     * @return mixed
     */
    public function push(array $messageAttributes, array $messageBody) {
        
        $this->model->push($messageAttributes,$messageBody);
        
    }
    
    /**
     * Receive and delete messages
     * @param array $options
     * @return mixed
     */
    public function pop(int $maxNumberOfMessages = self::DEFAULT_NUMBER_OF_MESSAGES, array $messageAttributeNames = ['All']) {
        
        $messages = $this->model->pop($maxNumberOfMessages,$messageAttributeNames);
        return $messages;
    }
    
}