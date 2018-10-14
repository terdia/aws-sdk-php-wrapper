<?php

namespace Devscreencast\S3Wrapper;

use Exception;

class FileUploadHelper
{

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public static function getFileSource($key)
    {
        if (!isset($_FILES[$key]['tmp_name'])) {
            throw new Exception(sprintf('Undefined key %s', $key));
        }
        return $_FILES[$key]['tmp_name'];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function guessUploadFileExtension()
    {
        if (!isset(array_keys($_FILES)[0])) {
            throw new Exception('The PHP _FILES array has no upload');
        }
        $key = array_keys($_FILES)[0];
        if (!isset($_FILES[$key]['name'])) {
            throw new Exception('The PHP _FILES array has no upload');
        }

        return pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
    }
}