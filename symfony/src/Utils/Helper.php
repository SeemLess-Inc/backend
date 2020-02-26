<?php
declare(strict_types=1);

namespace App\Utils;


class Helper
{

    /**
     * Accepts File Name. appends constant time() value to make file name unique.
     * Removes unwanted characters from provided string to make valid file or folder name string.
     * Returns file name and ext in array
     * @param $fileName
     * @return Array
     */
    public static function normalizeFileName($fileName, $isFile=true)
    {
        if($isFile)
        {
            //Get ext and filename
            $fileData = explode('.', $fileName);
            $string = $fileData[0];
            $fileExt = $fileData[1];
        } else {
            $string = $fileName;
        }

        $string = strip_tags($string);
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
        $string = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $string);
        $string = strtolower($string);
        $string = html_entity_decode( $string, ENT_QUOTES, "utf-8" );
        $string = htmlentities($string, ENT_QUOTES, "utf-8");
        $string = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $string);
        $string = str_replace(' ', '-', $string);
        $string = rawurlencode($string);
        $string = str_replace('%', '-', $string);

        if($isFile)
        {
            $data['name'] = $string;
            $data['ext'] = $fileExt;

            return $data;
        } else {
            return $string;
        }

    }

    /**
     * @param $path
     */
    public static function makeDirectory($path)
    {
        if(!is_dir($path))
        {
            mkdir($path, 0755, true);
        }
    }

}