<?php

namespace App\Repositories\Queues;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

abstract class BaseSQSRepository implements RepositoryInterface
{
    protected $client = null;
    protected $url = null;
    protected $model = null;
    
    const DEFAULT_NUMBER_OF_MESSAGES = 1;
    
    public function init($config,$model,$uri) {
        
        if (
                !isset($config['region']) || 
                !isset($config['version']) || 
                !isset($config['key']) || 
                !isset($config['secret']) ||
                !isset($config['prefix'])
                )
        {
            throw Exception('missing configuration!');
        }
        
        $this->setClient($config);
        $this->setUrl($config['prefix'].'/'.$uri);
        $this->setModel($model);
    }
    
    /**
     * Set Client
     * @return mixed
     */
    public function setClient(array $config){
       
        $options = array(
            'region' => $config['region'],
            'version' => $config['version'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ]
        );
        
        $this->client = new SqsClient($options);
    }
    
    /**
     * Set queue URL
     * @return mixed
     */
    public function setURL($url) {
        // TODO validate URL
        $this->url = $url;
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
        
        $messageAttributesKeys = array_keys($messageAttributes);
        $missingAttributesKeys = [];
        
        // check if body miss any keys
        foreach ($this->model->attributes as $field) {
            if (!in_array($field,$messageAttributesKeys)){
                $missingAttributesKeys[] = $field;
            }
        }
        
        if (!empty($missingAttributesKeys)) {
            throw Exception('missing attributes keys: '.implode($missingAttributesKeys,', '));
        }
        
        $messageBodyKeys = array_keys($messageBody);
        $missingBodyKeys = [];
        
        // check if body miss any keys
        foreach ($this->model->fields as $field) {
            if (!in_array($field,$messageBodyKeys)){
                $missingBodyKeys[] = $field;
            }
        }
        
        if (!empty($missingBodyKeys)) {
            throw Exception('missing body keys: '.implode($missingBodyKeys,', '));
        }
        
        $params = [
            'DelaySeconds' => 10,
            'MessageAttributes' => $messageAttributes,
            'MessageBody' => json_encode($messageBody),
            'QueueUrl' => $this->url
        ];
        
        try {
            $result = $this->client->sendMessage($params);
            var_dump($result);
            if ($result) {
                return true;
            }
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
            throw $e;
            
        }
    }
    
    /**
     * Receive and delete messages
     * @param array $options
     * @return mixed
     */
    public function pop(int $maxNumberOfMessages = self::DEFAULT_NUMBER_OF_MESSAGES, array $messageAttributeNames = ['All']) {
        
        if ($maxNumberOfMessages < 0 || $maxNumberOfMessages > 10 ) {
            throw \InvalidArgumentException("invalid maxNumberOfMessages");
        }
        
        try {
            $result = $this->client->receiveMessage(array(
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => $maxNumberOfMessages,
                'MessageAttributeNames' => $messageAttributeNames,
                'QueueUrl' => $this->url, // REQUIRED
                'WaitTimeSeconds' => 0,
            ));
            
            $queueMessages = [];
            if (count($result->get('Messages')) > 0) {
                //var_dump($result->get('Messages')[0]);
                $messages =  $result->get('Messages');
                
                $queueMessages.push($message);
                foreach ($messages as $message ) {
                    
                    // delete each fetched messages:
                    $result = $this->client->deleteMessage([
                        'QueueUrl' => $this->url, // REQUIRED
                        'ReceiptHandle' => $message['ReceiptHandle'] // REQUIRED
                    ]);
                }
            }
            return $queueMessages;
        } catch ( \Exception $ex ) {
            // rethrow
            error_log($e->getMessage());
            throw $ex; 
        }
        
    }
    
}