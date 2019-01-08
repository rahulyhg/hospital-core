<?php

namespace App\Helper;

class AwsS3
{
    private $_s3;
    
    public function __construct() {
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
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $key
        ]);
    }
    
    public function putObject($key, $file) {
        $this->_s3->putObject([
        	'Bucket' => config('filesystems.disks.s3.bucket'),
        	'Key'    => $key,
        	'SourceFile' => $file->getPathName(),
        	'ContentType' => $file->getMimeType()
        ]);
    }
}