<?php

namespace App\Models\User;

use Core\Helpers\Form;
use Core\Model;

class SignupForm extends Form
{
    protected $table = 'user';
    use Form;

    public static function checkFields(array $formFields)
    {
        (new self)->checkFormFields($formFields,);
    }
}
