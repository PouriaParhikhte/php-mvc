<?php

namespace Core\Middlewares;

use Core\Helper;
use Core\Validation;
use Exception;

class ImageUploader extends Validation
{
    public function __construct()
    {
        try {
            $this->requiredFile('my_file', 'تصویر');
            return $this;
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'upload');
        }
    }

    public function imageTypes(array $types)
    {
        try {
            if (!in_array($_FILES[$this->field]['type'], $types)) {
                $types = array_map([$this, 'getImageFormats'], $types);
                throw new Exception('فرمت های مجاز برای آپلود ' . implode(',', $types) . ' است', 302);
            }
            return $this;
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'upload');
        }
    }

    private function getImageFormats(string $type)
    {
        return substr($type, strlen('image/'));
    }

    public function imageSize(int $size)
    {
        try {
            $size = $size / 1000;
            if ($_FILES[$this->field]['size'] > $size)
                throw new Exception("اندازه مجار تصویر برای آپلود $size کیلوبایت است", 302);
            return $this;
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'upload');
        }
    }
}
