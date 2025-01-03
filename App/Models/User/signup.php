<?php

namespace App\Models\User;

use Core\Crud\Insert;
use Core\Helpers\Prototype;

class Signup extends Insert
{
    protected $table = 'user';
    use Prototype;

    public function userSignup(array $formData)
    {
        return $this->insert($formData)->getResult();
    }
}
