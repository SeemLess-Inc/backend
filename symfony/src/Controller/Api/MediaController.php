<?php

namespace App\Controller\Api;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     *
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
     * Creates Video frames using custom parameters
     *
     * Sample Input Data :
     *  [
     *   {
     *   "id":"",
     *   "start_off_set":"",
     *   "duration":"",
     *   "identity_name":""
     *   }
     * ]
     * @Route("/Api/CreateFrame", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function CreateFrame(Request $request)
    {
        return new Response('inprogress');
    }

    private function isJsonRequest(Request $request)
    {
        return 'json' === $request->getContentType();
    }
}



