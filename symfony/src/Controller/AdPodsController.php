<?php


namespace App\Controller;


use App\Entity\AdPods;
use App\Entity\Videos;
use App\Utils\Aws\AwsS3Util;
use App\Utils\FFMpeg\FFMpegUtil;
use App\Utils\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdPodsController extends AbstractController
{

    /**
     * Process New AdPods clip
     * @param $data
     * @return Response
     */
    public function createClip($data) : Response
    {
        $clipId = null;
        $statusCode = 500;
        $clipUrl = '';

        $videoId = $data['videos_id'];

        //Get Video information
        $entityManager = $this->getDoctrine()->getManager();

        $videoData = $entityManager->getRepository(Videos::class)->find($videoId);

        if($videoData !== null)
        {
            //Set Video Data
            //foreach($videoData as $video)
           // {
                $bucketName = $videoData->getBucketName();
                $fileName = $videoData->getName();
                $data['video_id'] = $videoData->getId()->getBytes();
            //}

            //Set Video Clip Data
            $startOffset = $data['start_off_set'];
            $duration = $data['duration'];
            $identityName = $data['identity_name'];

            //Set local and remote directory data
            $localDirectory = $this->getParameter('local_video_directory');
            $remoteDirectory = $this->getParameter('remote_video_directory');

            //Set remote folder as a main Video filename
            $remoteFolderData = Helper::normalizeFileName($fileName);
            $remoteFolder = $remoteFolderData['name'];

            //Get file extension
            $clipName = Helper::normalizeFileName($identityName, false).time().'.'.$remoteFolderData['ext'];

            //Set output filename
            $remoteOutputDir = $remoteDirectory.'/'.$remoteFolder;
            $localOutputDir = $localDirectory.'/'.$remoteOutputDir;

            Helper::makeDirectory($localOutputDir);

            //Getting S3 File
            $s3UtilObj = new AwsS3Util();
            $s3File = $s3UtilObj->get("{$bucketName}", "{$fileName}");

            //Generate Clip locally
            $ffmpegObj = new FFMpegUtil();
            $ffmpegObj->createChunk($startOffset, $duration, $s3File, $localOutputDir.'/'.$clipName);

            //Upload Clip to remote bucket
            //Upload Created Jpeg file to S3
            $clipUrl = $s3UtilObj->uploadFile(
                $bucketName,
                $remoteOutputDir.'/'.$clipName,
                $localOutputDir.'/'.$clipName
            );

            //Storing into database
            $data['clip_path'] = $clipUrl;
            $clipId = $this->createAdPod($data);

            $msg = "Video clip created successfully.";
            $statusCode = 200;

        } else {
            $msg = "Provided video id is invalid!";
            $statusCode = 400;
        }
        // Create Response
        $response = new JsonResponse();
        $response->setData([
            'statusCode' => $statusCode,
            'msg' => $msg,
            'clipUrl' => $clipUrl
        ]);

        return $response;
    }

    /**
     * Retusn JSON response with all available clips
     *
     * @param $data
     * @return JsonResponse
     */
    public function getClip($data)
    {
        $statusCode = 500;
        $msg = "";
        $clipData = array();
        $videoId = $data['videos_id'];
        if($videoId !== ''){
            //Get Video information
            $entityManager = $this->getDoctrine()->getManager();

            $videoData = $entityManager->getRepository(AdPods::class)->findAll();
            $statusCode = 200;

            if($videoData !== null)
            {
                foreach($videoData as $key => $video)
                {
                    $clipData[$key]['identityName'] = $video->getIdentityName();
                    $clipData[$key]['clipUrl'] = $video->getClipPath();
                    $clipData[$key]['metadata'] = $video->getMetadata();
                    $clipData[$key]['startOffSet'] = $video->getStartOffSet();
                    $clipData[$key]['duration'] = $video->getDuration();
                }
            }
        } else {
            $statusCode = 400;
            $msg = "Invalid Video Id.";
            $clipData = null;
        }

        // Create Response
        $response = new JsonResponse();
        $response->setData([
            'statusCode' => $statusCode,
            'msg' => $msg,
            'data' => $clipData
        ]);

        return $response;
    }

    /**
     * Create Database Entry
     * @param Array $data
     * @return int|null
     */
    private function createAdPod($data){
        $entityManager = $this->getDoctrine()->getManager();

        $adPod = new AdPods();
        $adPod->setVideosId($data['video_id']); // Binary value retrieved from database
        $adPod->setStartOffset($data['start_off_set']);
        $adPod->setDuration($data['duration']);
        $adPod->setClipPath($data['clip_path']);
        $adPod->setIdentityName($data['identity_name']);
        $adPod->setMetadata($data['metadata']);

        //Making Doctorine ready to save AdPod eventually
        $entityManager->persist($adPod);

        // Asking Doctorine to save the data
        $entityManager->flush();

        return $adPod->getId();
    }
}