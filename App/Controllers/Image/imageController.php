<?php

namespace App\Controllers\Image;

use Core\Controller;
use Core\Helpers\Upload;
use Exception;

class ImageController extends Controller
{
    public function uploadPostImage()
    {
        try {
            Upload::getInstance()->image();
            // ->checkImageType(['png']);
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }
}
