<?php
namespace App\Repositories\Sqs;

interface RepositoryInterface
{
    
    /**
     * Set Model
     * @return mixed
     */
    public function setModel($model);

    /**
     * push item to queue
     * @param $options
     * @return mixed
     */
    public function push(array $messageAttributes, array $messageBody);

    /**
     * pop items out of queue
     * @param array $options
     * @return mixed
     */
    public function pop(int $maxNumberOfMessages, array $messageAttributeNames);

   
}