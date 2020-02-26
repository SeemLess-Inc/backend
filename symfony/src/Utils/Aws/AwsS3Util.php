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
     *
     * Uploads file to S3 Bucket
     *
     * @param string $bucket
     * @param string $path
     * @param string $name
     * @return string
     */
    public function uploadFile(string $bucket, string $path, string $name): string
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


    /**
     * Multipart file Upload to S3
     *
     */
    public function multipartUploadFile(){
        //Multipart File Upload
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1'
        ]);
/*
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
*/
        $bucket = 'mediaconvertbktoutput';
        $keyname = 'testuploading.mp4';
        $filename = '/Users/hims//largevideo/Screen Recording 2020-01-27 at 5.34.50 AM.mp4';

        $result = $s3->createMultipartUpload([
            'Bucket'       => $bucket,
            'Key'          => $keyname,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'ACL'          => 'public-read',
            'Metadata'     => [
                'param1' => 'value 1',
                'param2' => 'value 2',
                'param3' => 'value 3'
            ]
        ]);
        $uploadId = $result['UploadId'];

        // Upload the file in parts.
        try {
            $file = fopen($filename, 'r');
            $partNumber = 1;
            while (!feof($file)) {
                echo "Uploading part {$partNumber} of {$filename}." . PHP_EOL;

                $result = $s3->uploadPart([
                    'Bucket'     => $bucket,
                    'Key'        => $keyname,
                    'UploadId'   => $uploadId,
                    'PartNumber' => $partNumber,
                    'Body'       => fread($file, 5 * 1024 * 1024),
                ]);
                $parts['Parts'][$partNumber] = [
                    'PartNumber' => $partNumber,
                    'ETag' => $result['ETag'],
                ];
                $partNumber++;


            }
            fclose($file);
        } catch (S3Exception $e) {
            $result = $s3->abortMultipartUpload([
                'Bucket'   => $bucket,
                'Key'      => $keyname,
                'UploadId' => $uploadId
            ]);

            echo "Upload of {$filename} failed." . PHP_EOL;
        }

        // Complete the multipart upload.
        $result = $s3->completeMultipartUpload([
            'Bucket'   => $bucket,
            'Key'      => $keyname,
            'UploadId' => $uploadId,
            'MultipartUpload'    => $parts,
        ]);
        return $result['Location'];
    }
}