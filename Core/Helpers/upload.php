<?php

namespace Core\Helpers;

use Exception;

class Upload
{
    use Prototype;

    public function image()
    {
        $uploadExceptions = [
            UPLOAD_ERR_INI_SIZE => 'Too large',
            UPLOAD_ERR_FORM_SIZE => 'Too large',
            UPLOAD_ERR_NO_FILE => 'Upload an image file',
            UPLOAD_ERR_NO_TMP_DIR => 'No tmp dir',
            UPLOAD_ERR_EXTENSION => 'Wrong type',
        ];
        echo $_FILES['my_file']['error'][0];
        if ($_FILES['my_file']['error'] !== 0)
            var_dump($uploadExceptions[$_FILES['my_file']['error'][0]]);
        exit;
        throw new Exception($uploadExceptions[$_FILES['my_file']['error']]);


        return (new self);
    }

    public function checkImageType(array $types)
    {
        if (!in_array($_FILES['my_file']['type'], $types))
            exit('wrong type!');
        return $this;
    }

    public function checkImageSize()
    {
        if ($_FILES['my_file']['size'] > SETTINGS->IMAGEUPLOADSIZE)
            exit('Too large!');
        return $this;
    }
}
