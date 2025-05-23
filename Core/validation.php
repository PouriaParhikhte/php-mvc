<?php

namespace Core;

use Core\Helpers\Http;
use Core\Helpers\Prototype;
use Exception;

class Validation
{
    use Prototype;

    private $value, $field, $alternative;

    public function field($name, $alternativeName = null)
    {
        $this->value = Http::request()->$name;
        $this->field = $name;
        $this->alternative = $alternativeName;
        return $this;
    }

    public function fields(array $name, array $alternativeName = [])
    {
        foreach ($name as $key => $val) {
            $this->value = Http::request()->$val;
            if (!$this->value) {
                $this->field[$key] = $name[$key];
                $this->alternative[$key] = $alternativeName[$key] ?? null;
            }
        }
        return $this;
    }

    public function required()
    {
        if (($this->field || $this->alternative) && !$this->value) {
            $message = 'FIELD الزامی است';
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function allRequired(array $input = [])
    {
        if (isset($input) && array_search('', $input)) {
            $message = 'فیلدهای خالی را پرکنید';
            $this->field = $input;
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function requiredFile($name, $alternativeName = null)
    {
        $this->field = $name;
        $this->alternative = $alternativeName;
        if (!$_FILES[$this->field]['name'] ?: !$_FILES[$this->field]['name'][0]) {
            $message = "آپلود FIELD الزامی است";
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function int()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_INT;
            $filterResult = $this->result($this->value, $filter);
            if ($filterResult === FALSE || !is_numeric($this->value)) {
                $field = $this->alternative ?? $this->field;
                $message = "از اعداد صحیح برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function unsignedInteger($minRange = 0, $maxRange = PHP_INT_MAX)
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_INT;
            $options['options']['min_range'] = $minRange;
            $options['options']['max_range'] = $maxRange;
            $filterResult = $this->result($this->value, $filter, $options);

            if ($filterResult === false) {
                $field = $this->alternative ?? $this->field;
                if ($this->value < $minRange)
                    $message = "حداقل مقدار برای $field $minRange است";
                elseif (is_numeric($this->value) && $this->value > $minRange)
                    $message = "حداکثر مقدار برای $field $maxRange است";
                else
                    $message = "از اعداد صحیح مثبت برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function signedInteger($minRange = PHP_INT_MIN, $maxRange = -1)
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_INT;
            $options['options']['min_range'] = $minRange;
            $options['options']['max_range'] = $maxRange;
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "از اعداد صحیح منفی برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function email()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $regexp = '/^[_a-z]+[0-9-]*' . '(\.[_a-z0-9-]+)*' . '\@[a-z0-9-]+' . '(\.[a-z0-9-]+)*' . '(\.[a-z]{2,})$/i';
            $options['options']['regexp'] = $regexp;
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "فرمت $field نامعتبر میباشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function url()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $regexp = '/^(http:\/\/)|(https:\/\/)?[a-z0-9-_\/\.]+$/i';
            $options['options']['regexp'] = $regexp;
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "فرمت $field نامعتبر میباشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function englishLetters()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $options['options']['regexp'] = '/^[a-z ]+$/i';
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "از خروف الفبا انگلیسی برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function alphaNumericEnglishLetters()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $options['options']['regexp'] = '/^[a-z0-9 ]+$/i';
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "از خروف الفبا انگلیسی برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function lowecaseLetters()
    {
        if ($this->value !== '' && !ctype_lower($this->value)) {
            $field = $this->alternative ?? $this->field;
            $message = "از خروف کوچک الفبا انگلیسی برای $field استفاده کنید";
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function uppercaseLetters()
    {
        if ($this->value !== '' && !ctype_upper($this->value)) {
            $field = $this->alternative ?? $this->field;
            $message = "از خروف بزرگ الفبا انگلیسی برای $field استفاده کنید";
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function persianLetters()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $options['options']['regexp'] = "/^[ آ-ی]+$/u";
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "از حروف الفبا فارسی برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function alphaNumericPersianLetters()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $options['options']['regexp'] = "/^[ 0-9آ-ی]+$/u";
            $filterResult = $this->result($this->value, $filter, $options);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message = "از حروف الفبا فارسی برای $field استفاده کنید";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function length(int $length)
    {
        if ($this->value !== '' && strlen($this->value) !== $length) {
            $message = "طول $this->field باید $length باشد";
            $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function pattern(string $pattern, $minLength = '', $maxLength = '')
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_REGEXP;
            $options['options']['regexp'] = $pattern;
            $filterResult = $this->result($this->value, $filter, $options);
            $field = $this->alternative ?? $this->field;
            if (!$filterResult)
                $message = "$field نامعتبر میباشد";
            if (is_numeric($this->value) && ($minLength !== '' || $maxLength !== '')) {
                if (strlen($this->value) < $minLength)
                    $message = "حداقل طول $field باید $minLength باشد";
                elseif (strlen($this->value) > $maxLength)
                    $message = "حداکثر طول $field باید $maxLength باشد";
            }
            if (isset($message))
                $this->replacePlaceholderWithValueAndThrowException($message);
        }
        return $this;
    }

    public function boolean()
    {
        if ($this->value !== '') {
            $booleans = [0, 1, 'yes', 'no', 'true', 'false'];
            $value = strtolower($this->value);
            if (!in_array($value, $booleans)) {
                $booleans = implode(',', $booleans);
                $field = $this->alternative ?? $this->field;
                $message = "مقادیر $booleans برای $field مجاز است";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function double()
    {
        if ($this->value !== '') {
            $filter = FILTER_VALIDATE_FLOAT;
            $filterResult = $this->result($this->value, $filter);
            if ($filterResult === FALSE) {
                $field = $this->alternative ?? $this->field;
                $message =
                    "اعداد اعشاری یا مقادیر بزرگ عددی برای $field مجاز است";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function minLength($len)
    {
        if ($this->value !== '') {
            $valueLength = mb_strlen($this->value);
            if ($valueLength < $len) {
                $field = $this->alternative ?? $this->field;
                $message = "طول $field نباید کمتر از $len کاراکتر باشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function maxLength($len)
    {
        if ($this->value !== '') {
            $valueLength = mb_strlen($this->value);
            if ($valueLength > $len) {
                $field = $this->alternative ?? $this->field;
                $message = "طول $field نباید بیشتر از $len کاراکتر باشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        }
        return $this;
    }

    public function min($value)
    {
        if ($this->value !== '')
            if ($this->value < $value) {
                $field = $this->alternative ?? $this->field;
                $message = "مقدار $field نباید کمتر از $value باشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        return $this;
    }

    public function max($value)
    {
        if ($this->value !== '')
            if ($this->value > $value) {
                $field = $this->alternative ?? $this->field;
                $message = "مقدار $field نباید بیشتر از $value باشد";
                $this->replacePlaceholderWithValueAndThrowException($message);
            }
        return $this;
    }

    private function result($value, $filter, $options = []): mixed
    {
        return filter_var($value, $filter, $options);
    }

    private function replacePlaceholderWithValueAndThrowException($message): never
    {
        $search = 'FIELD';
        $replace = $this->alternative ?? $this->field;
        if (is_array($replace))
            $replace = implode(' و ', $replace);
        $message = str_replace($search, $replace, $message);
        throw new Exception($message, 302);
    }
}
