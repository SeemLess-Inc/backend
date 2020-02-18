<?php
declare(strict_types=1);

namespace App\Utils\Aws;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AwsS3Util
{
    private $S3;

    public function __construct()
    {
        $this->S3 = new S3Client([
            'signature' => 'v4',
            'profile' => 'default',
            'region' => 'eu-west-1',
            'version' => 'latest'
        ]);
    }

    /**
     * @param string $bucket
     * @param string $path
     * @param string $name
     * @return string
     */
    public function putObject(string $bucket, string $path, string $name): string
    {
        try {
            $result = $this->S3->putObject([
                'Bucket' => $bucket,
                'Key' => $path,
                'SourceFile' => $name
            ]);
            //$this->S3->waitUntil('ObjectExists', ['Bucket' => $bucket, 'Key' => $name]);

            return $result['ObjectURL'];
        } catch (AwsException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param string $bucket
     * @param string $name
     * @return mixed
     */
    public function get(string $bucket, string $name)
    {
        try{
            $result = $this->S3->getObject(['Bucket' => $bucket, 'Key' => $name]);
        } catch (AwsException $e) {
            return $e->getMessage();
        }
        return $result->get('@metadata')['effectiveUri'];
    }




    /*
    public function multipartUpload(){
        //Multipart File Upload
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1'
        ]);

        // Prepare the upload parameters.
        $uploader = new MultipartUploader($s3, '/Users/hims/largevideo/clip720.mov', [
            'bucket' => 'mediaconvertbktoutput',
            'key'    => 'large/clip720.mov'
        ]);

        // Perform the upload.
        try {
            $result = $uploader->upload();
            echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }*/
}