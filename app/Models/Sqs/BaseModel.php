<?php
namespace App\Models\Sqs;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

abstract class BaseModel
{
    
    protected $client = null;
    protected $url = null;
    // attributes of SQS messages
    public $attributes = [];
    // fields of SQS messages
    public $fields = [];
    
    public $skipCheckFields = false;
    
    public $message = null;
    
    const DEFAULT_NUMBER_OF_MESSAGES = 1;
    
    public function  __construct($message = null) {
        if ($message) {
            $this->message = $message;
        }
    }
    
    public function init($uri) {
        $config = config('queue.connections.sqs');
        if (
                !isset($config['region']) || 
                !isset($config['version']) || 
                !isset($config['key']) || 
                !isset($config['secret']) ||
                !isset($config['prefix'])
                )
        {
            throw \Exception('missing configuration!');
        }
        
        $this->setClient($config);
        $this->setUrl($config['prefix'].'/'.$uri);
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
     * push item to queue
     * @param $messageAttributes, $messageBody
     * @return mixed
     */
    public function push(array $messageAttributes, array $messageBody) {
        
        $messageAttributesKeys = array_keys($messageAttributes);
        $missingAttributesKeys = [];
        
        // check if body miss any keys
        foreach ($this->attributes as $attribute) {
            if (!in_array($attribute, $messageAttributesKeys)){
                $missingAttributesKeys[] = $attribute;
            }
        }
        
        if (!empty($missingAttributesKeys)) {
            throw \Exception('missing attributes keys: '.implode($missingAttributesKeys,', '));
        }
        
        if ($this->skipCheckFields === false) {
            $messageBodyKeys = array_keys($messageBody);
            $missingBodyKeys = [];
            
            // check if body miss any keys
            foreach ($this->fields as $field) {
                if (!in_array($field,$messageBodyKeys)){
                    $missingBodyKeys[] = $field;
                }
            }
            
            if (!empty($missingBodyKeys)) {
                throw new \Exception('missing body keys: '.implode($missingBodyKeys,', '));
            }
        }
        
        
        $params = [
            'DelaySeconds' => 10,
            'MessageAttributes' => $messageAttributes,
            'MessageBody' => json_encode($messageBody),
            'QueueUrl' => $this->url
        ];
        
        try {
            $result = $this->client->sendMessage($params);
            //var_dump($result);
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
                
                
                foreach ($messages as $message ) {
                    $queueMessages[] = new static($message);
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
            echo (get_class($ex));
            throw $ex; 
        }
        
    }
    
    public function setMessage() {
        
    }
    
    public function getBody() {
        echo "getting body:::";
        //var_dump($this->message['Body']);
        return json_decode($this->message['Body'],true);
    }
    
}
