<?php

namespace App\Controller\Api;

use App\Utils\Aws\AwsS3Util;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{

    /**
     * Creates initial entry, about uploaded video file, into database.
     * Sample input Data :
     * {
     *  "bucket_name":"elasticbeanstalk-eu-west-1-060643667111",
     *  "object_name":"",
     *  "media_file":"sample.mp4",
     *  "desc":"test Media File Sample",
     *  "metadata":"metada information",
     *  "thumbnail_path":""
     * }
     * @Route("/Api/CreateVideoEntry", methods={"POST"})
     * @param Request $request
     * @return Response
     * @TODO Add Data Validation
     */
    public function createVideoEntry(Request $request)
    {
        //Checking invalid input
        if(!$this->isJsonRequest($request)){
            return Response::create('Invalid Input!', 500);
        }
        $request = json_decode($request->getContent(), true);
        $fileInfo = array(
                    'data'=>
                        array(
                            'bucket_name'=>$request['bucket_name'],
                            'folder_path'=>$request['object_name'],
                            'name'=>$request['media_file'],
                            'description'=>$request['desc'],
                            'metadata'=>$request['metadata'],
                            'thumbnail_path'=>$request['thumbnail_path']
                        )
                );

        $response = $this->forward('App\Controller\VideosController::setupVideoEntry', $fileInfo);

        return $response;
    }

    /**
     * @Route("/Api/MultipartUpload")
     * @return Response
     * @TODO Add Data Validation
     */
    public function multipartUpload(){
        //Getting S3 File
        $s3UtilObj = new AwsS3Util();
        $s3File = $s3UtilObj->multipartUploadFile();

        // Create Response
        $response = new JsonResponse();
        $response->setData([
            'msg' => 'Data Saved Successfully.',
            'id' => $s3File
        ]);

        return new Response($response);
    }

    /**
     * Creates Video clip/frames using custom parameters
     *
     * Sample Input Data :
     *
     *   {
     *   "id":"",
     *   "start_off_set":"",
     *   "duration":"",
     *   "identity_name":"",
     *   "metadata":""
     *   }
     *
     * @Route("/Api/CreateClip", methods={"POST"})
     * @param Request $request
     * @return Response
     * @TODO Add data validation
     */
    public function processNewClip(Request $request)
    {
        //Checking invalid input
        if(!$this->isJsonRequest($request)){
            return Response::create('Invalid Input!', 500);
        }

        $request = json_decode($request->getContent(), true);
        $fileInfo = array(
            'data'=>
                array(
                    'videos_id'=>$request['id'],
                    'start_off_set'=>$request['start_off_set'],
                    'duration'=>$request['duration'],
                    'identity_name'=>$request['identity_name'],
                    'metadata'=>$request['metadata']
                )
        );

        $response = $this->forward('App\Controller\AdPodsController::createClip', $fileInfo);

        return $response;
    }

    private function isJsonRequest(Request $request)
    {
        return 'json' === $request->getContentType();
    }
}



