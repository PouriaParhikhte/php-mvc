<?php

namespace App\Api\Panel;

use App\Models\Panel\Login;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Token\CreateToken;
use Core\Validation;
use Exception;

class PanelLoginController extends Controller
{
    public function login(Validation $validation)
    {
        try {
            $this->formValidation($validation)
                ->fetchUserFromTable($this->params['username'], $admin)
                ->checkIfUserExistsAndPasswordIsCorrect($admin, $this->params['password'])
                ->storeUserIdAndUserRoleIntoJwt($admin->userId, $admin->roleId)
                ->loadPanelDashboard();
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }

    private function formValidation($validation)
    {
        $validation->allRequired($this->params);
        return $this;
    }

    private function fetchUserFromTable($username, &$user)
    {
        $user = Login::fetchUser($username);
        return $this;
    }

    private function checkIfUserExistsAndPasswordIsCorrect($user, $userPassword)
    {
        if (!isset($user) || ($this->createPasswordHash($userPassword, strtotime($user->createdAt)) !== $user->password))
            throw new Exception('نام کاربری یا رمز عبور اشتباه است', 302);
        return $this;
    }

    private function createPasswordHash($userPassword, $timestamp)
    {
        $userPassword .= $timestamp;
        return md5($userPassword);
    }

    private function storeUserIdAndUserRoleIntoJwt($userId, $roleId)
    {
        CreateToken::getInstance()->create(['userId' => $userId, 'roleId' => $roleId]);
        return $this;
    }

    private function loadPanelDashboard()
    {
        PanelDashboardController::index();
    }
}
