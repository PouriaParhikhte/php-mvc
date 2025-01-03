<?php

namespace App\Models\User;

use Core\Helpers\Form;

class LoginForm extends Form
{
    protected $table = 'user';
    use Form;

    public static function checkFields(array $formFields)
    {
        (new self)->checkFormFields($formFields, ['userId', 'status', 'roleId', 'createdAt', 'updatedAt']);
    }
}
