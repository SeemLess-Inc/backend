<?php
declare(strict_types=1);

namespace App\Utils\FFMpeg;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
* FFMpeg utility class
*/
class FFMpegUtil
{
    /**
     * Creates Thumbnail Image out of first second frame from the provided video location from bucket
     * @TODO We may need to decide thumbnail image Size
     * @param $location
     * @param $mediaFile
     * @param $thumbnailName
     */
    public function createThumbnail($mediaFile, $thumbnailName){
        //Data Preparation
        $interval = 1;
        $size = '640x480';

        //@TODO Frame time '00:00:01' dynamic
        // FFMpeg command build up and Execution
        $process = Process::fromShellCommandLine('ffmpeg -i "{:$mediaFile}" -deinterlace -an -ss "{:$interval}" -t 00:00:01 -r 1 -y -s "{:$size}" -vcodec mjpeg -f mjpeg "{:$thumbnailName}" 2>&1');
        $process->run(null,
            [
                'mediaFile' => $mediaFile,
                'interval' => $interval,
                'size' => $size,
                'thumbnailName' => $thumbnailName
            ]
        );
    }

    /**
     * Creates Video clips using provided data
     * @param $startOffset
     * @param $duration
     * @param $inputFile
     * @param $outputFile
     */
    public function createChunk($startOffset, $duration, $inputFile, $outputFile){
        
        //FFMpeg command preperation and Execution
        $process = Process::fromShellCommandLine('ffmpeg -i "{:$inputFile}" -ss {:$startOffset} -t {:$duration} -async 1 {:$outputFile}');
        $process->run(null,
            [
                'inputFile' => $inputFile,
                'startOffset' => $startOffset,
                'duration' => $duration,
                'outputFile' => $outputFile
            ]
        );
    }
}