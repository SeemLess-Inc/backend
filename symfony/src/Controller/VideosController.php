<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Utils\Aws\AwsS3Util;
use App\Utils\FFMpeg\FFMpegUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideosController extends AbstractController
{
    //private string
    /**
     *
     * @param Array $data
     * @return Response
     */
    public function setupVideoEntry($data) : Response
    {
        // If thumbnail is not created then need to create one
        if(!strlen($data['thumbnail_path']) > 0) {

            $bucketNameData = $data['bucket_name'];
            $fileNameData = $data['name'];
            $folderPathData = $data['folder_path'];

            //Getting S3 File
            $s3UtilObj = new AwsS3Util();
            $s3File = $s3UtilObj->get("{$bucketNameData}", "{$fileNameData}");

            //Naming Thumbnail image using video file name
            $thumbnailName = preg_replace('/.[^.]*$/', '', $fileNameData).".jpg";   // same as video file name
            $localDirectory = $this->getParameter('image_directory');

            //Function call to create Thumbnail Image.
            $ffmpegObj = new FFMpegUtil();
            $ffmpegObj->createThumbnail($s3File, $localDirectory.'/'.$thumbnailName);

            $remoteLocation = 'thumbnails/'.$thumbnailName;
            if(strlen($folderPathData) > 0){
                $remoteLocation = $folderPathData.'/thumbnails/'.$thumbnailName;
            }
            //Upload Created Jpeg file to S3
            $s3UtilObj->putObject(
                $bucketNameData,
                $remoteLocation,
                $localDirectory.'/'.$thumbnailName
            );
            $data['thumbnail_path'] = $bucketNameData.'/'.$remoteLocation;
        }

        $newVideoId = $this->createVideos($data);

        // Create Response
        $response = new JsonResponse();
        $response->setData([
            'msg' => 'Data Saved Successfully.',
            'id' => $newVideoId
        ]);

        return new Response($response);
    }

    /**
     * Create Database Entry
     * @param Array $data
     */
    private function createVideos($data){
        $entityManager = $this->getDoctrine()->getManager();

        $video = new Videos();
        $video->setBucketName($data['bucket_name']);
        $video->setFolderPath($data['folder_path']);
        $video->setName($data['name']);
        $video->setDescription($data['description']);
        $video->setMetadata($data['metadata']);
        $video->setThumbnailPath("{$data['thumbnail_path']}");

        //Making Doctorine ready to save Videos eventually
        $entityManager->persist($video);

        // Asking Doctorine to save the data
        $entityManager->flush();

        return $video->getId();
    }
}
