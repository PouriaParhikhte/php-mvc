<?php

namespace Core\Middlewares;

use Core\Helper;
use Core\Helpers\Http;
use Core\Validation;
use Exception;

class UrlValidation extends Validation
{
    public function validate()
    {
        try {
            $this->field('url', 'نام انگلیسی صفحه')->required()->alphaNumericEnglishLetters()->field('persianUrl', 'نام فارسی صفحه')->required()->alphaNumericPersianLetters()->field('sort', 'ترتیب')->required()->unsignedInteger(1);
            if (isset(Http::request()->parentId))
                $this->field('parentId', 'لینک والد')->unsignedInteger();
            return Helper::parentObject();
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
