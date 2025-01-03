<?php

namespace App\Api\User;

use App\Models\User\CheckIfUsernameExists;
use App\Models\User\Signup;
use Core\Controller;
use Core\Helpers\Form;
use Core\Helpers\Helper;
use Core\Validation;
use Exception;

class SignupController extends Controller
{

    public function create(Validation $validation)
    {
        try {
            Form::getInstance()->__invoke('user')->checkFormFields($this->params, ['userId', 'status', 'roleId', 'createdAt', 'updatedAt']);
            $this->formValidation($validation)->checkDuplicateUsername($this->params['username'])->securePassword($this->params['password'])->register($this->params);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'signupErrorMessage');
        }
    }

    private function formValidation($validation)
    {
        $validation->allRequired($this->params)->field('username', 'نام کاربری')->persianLetters()->minLength(3)->field('password', 'رمز عبور')->minLength(5);
        return $this;
    }

    private function checkDuplicateUsername($username)
    {
        if (CheckIfUsernameExists::check($username))
            throw new Exception('این نام کاربری قبلا ثبت شده است. نام دیگری را انتخاب کنید');
        return $this;
    }

    private function securePassword(&$password)
    {
        Helper::createPasswordHash($password);
        return $this;
    }

    private function register($formValues)
    {
        $message = Signup::getInstance()->userSignup($formValues) ? 'ثبت نام شما با موفقیت انجام شد' : 'ثبت نام شما با خطا همراه شد';
        throw new Exception($message);
    }
}
