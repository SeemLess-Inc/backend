<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Utils\Aws\AwsS3Util;
use App\Utils\FFMpeg\FFMpegUtil;
use App\Utils\Helper;
use phpDocumentor\Reflection\Types\Object_;
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
    public function setupVideoEntry($data)
    {
        // If thumbnail is not created then need to create one
        if(!strlen($data['thumbnail_path']) > 0)
        {
            // Set Video Data
            $bucketName = $data['bucket_name'];
            $fileName = $data['name'];
            $folderPath = $data['folder_path'];

            //Set local and remote directory data
            $localDirectory = $this->getParameter('local_image_directory');
            $remoteDirectory = $this->getParameter('remote_image_directory');

            //Naming Thumbnail image using video file name. Appending Time at the end to make it unique name
            $fileNameData = Helper::normalizeFileName($fileName);
            $thumbnailName = $fileNameData['name'].".jpg";   // same as video file name

            //Setting up Remote thumbnail location
            $remoteLocation = $remoteDirectory.'/'.$thumbnailName;
            if(strlen($folderPath) > 0)
            {
                $folderPathData = Helper::normalizeFileName($folderPath, false);
                $remoteLocation = $remoteDirectory.'/'.$folderPathData.'/'.$thumbnailName;
            }

            //Getting S3 File
            $s3UtilObj = new AwsS3Util();
            $s3File = $s3UtilObj->get("{$bucketName}", "{$fileName}");
            //@TODO Check if thumb nail is created after ffmpeg process. If not then throw an error

            //Function call to create Thumbnail Image.
            $ffmpegObj = new FFMpegUtil();
            $ffmpegObj->createThumbnail($s3File, $localDirectory.'/'.$thumbnailName);

            //Upload Created Jpeg file to S3
            $s3UtilObj->uploadFile(
                $bucketName,
                $remoteLocation,
                $localDirectory.'/'.$thumbnailName
            );
            $data['thumbnail_path'] = $bucketName.'/'.$remoteLocation;
        }

        $newVideoId = $this->createVideos($data);

   //     return $newVideoId;

        // Create Response
        $response = new JsonResponse();
        $response->setData([
            'msg' => 'Data saved successfully.',
            'id' => $newVideoId
        ]);

        return new Response($response);

    }

    /**
     * Returns Video object data for the provided video id.
     * @param $id
     * @return Videos Object|null
     */
    public function getVideo($id)
    {
        if($id !== '')
        {
            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager = $this->getEntityManager();

            return $entityManager->getRepository(Videos::class)->findByVideoId($id);;

        }
        return null;
    }
    /**
     * Create Database Entry
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
