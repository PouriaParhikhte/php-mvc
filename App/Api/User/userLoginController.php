<?php

namespace App\Api\User;

use App\Models\User\Login;
use Core\Controller;
use Core\Helpers\Form;
use Core\Helpers\Helper;
use Core\Helpers\Token;
use Core\Helpers\Token\CreateToken;
use Core\Validation;
use Exception;

class UserLoginController extends Controller
{
    public function login()
    {
        try {
            $this->checkFormFields()
                ->formValidation()
                ->fetchUserFromTable($this->params['username'])
                ->loadUserDashboard();
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'loginErrorMessage');
        }
    }

    private function checkFormFields()
    {
        Form::getInstance()->__invoke('user')->checkFormFields($this->params, ['userId', 'status', 'roleId', 'createdAt', 'updatedAt']);
        return $this;
    }

    private function formValidation()
    {
        Validation::getInstance()->fields(['username', 'password'], ['نام کاربری', 'رمز عبور'])->required();
        return $this;
    }

    private function fetchUserFromTable($username)
    {
        $user = Login::fetchUser($username);
        $this->checkUIfUserExistsAndPasswordIsCorrect($user, $this->params['password']);
        CreateToken::getInstance()->create(['userId' => $user->userId, 'roleId' => $user->roleId]);
        return $this;
    }

    private function checkUIfUserExistsAndPasswordIsCorrect($userObject, $userPassword)
    {
        if (!isset($userObject) || !Helper::passwordVerify($userPassword, $userObject->password))
            throw new Exception('نام کاربری یا رمز عبور اشتباه است', 302);
        return $this;
    }

    private function loadUserDashboard()
    {
        $headers = getallheaders();
        if (!isset($headers['type']) || $headers['type'] !== 'xhr')
            Helper::redirectTo('api/user/dashboard');
    }
}
