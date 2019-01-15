<?php

namespace App\Helper;

class AwsS3
{
    private $_s3;
    private $_bucket;
    
    public function __construct($bucket = null) {
        if($bucket === null) {
            $this->_bucket = config('filesystems.disks.s3.bucket');
        }
        else {
            $this->_bucket = $bucket;
        }
        
        $this->_s3 = new \Aws\S3\S3Client([
        	'region'  => config('filesystems.disks.s3.region'),
        	'version' => 'latest',
        	'credentials' => [
        	    'key'    => config('filesystems.disks.s3.key'),
        	    'secret' => config('filesystems.disks.s3.secret'),
        	]
        ]);  
    }
    
    public function deleteObject($key) {
        $this->_s3->deleteObject([
            'Bucket' => $this->_bucket,
            'Key'    => $key
        ]);
    }
    
    public function putObject($key, $pathName, $mimeType) {
        $this->_s3->putObject([
        	'Bucket' => $this->_bucket,
        	'Key'    => $key,
        	'SourceFile' => $pathName,
        	'ContentType' => $mimeType
        ]);
    }
}