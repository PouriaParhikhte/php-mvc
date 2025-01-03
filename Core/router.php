<?php

namespace Core;

use App\Api\Panel\ManagerController;
use App\Api\Panel\PanelDashboardController;
use App\Api\Panel\PanelLoginController;
use App\Api\Panel\PanelLogoutController;
use App\Api\Ticket\AnswerController;
use App\Api\Ticket\AnswerTicketController;
use App\Api\Ticket\CreateTicketController;
use App\Api\Ticket\ReceivedTicketsController;
use App\Api\Ticket\RegisterTicketController;
use App\Api\Ticket\TicketIndexController;
use App\Api\Ticket\ViewTicketController;
use App\api\user\SignupController;
use App\Api\User\UserDashboardController;
use App\Api\User\UserLoginController;
use App\Api\User\UserLogoutController;
use App\Controllers\DatabaseController;
use App\Controllers\MainController;
use Core\Helpers\Helper;
use Core\Helpers\Token\CreateToken;
use Core\Helpers\Token\GetDecodedToken;

class Router extends Route
{
    public function __construct($url)
    {
        $url = trim($url, '/');
        if (Helper::getRequestMethod())
            CreateToken::getInstance(['url' => $url]);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        match ($requestMethod) {
            'GET' => $this->getRequests($url),
            'POST' => $this->postRequests($url),
        };
    }

    private function getRequests($url): void
    {
        $components = $this->urlToArray($url, $id);
        match ($url) {
            'database/tables' => $this->isAdmin()->loadControllerAndAction(DatabaseController::class, 'create', $components),
            'api/user/dashboard' => $this->isUser()->loadControllerAndAction(UserDashboardController::class, 'dashboard', $components),
            'api/user/logout' => $this->isUser()->loadControllerAndAction(UserLogoutController::class, 'logout', $components),
            'api/ticket/create' => $this->isUser()->loadControllerAndAction(CreateTicketController::class, 'create', $components),
            "api/ticket/index$id" => $this->isUser()->loadControllerAndAction(TicketIndexController::class, 'index', $components),
            "api/ticket/received$id"  => $this->isAdmin()->loadControllerAndAction(ReceivedTicketsController::class, 'index', $components),
            "api/ticket/answer$id"  => $this->isAdmin()->loadControllerAndAction(AnswerController::class, 'create', $components),
            "api/ticket/view$id"  => $this->isUser()->loadControllerAndAction(ViewTicketController::class, 'index', $components),
            'api/panel/manager' => $this->loadControllerAndAction(ManagerController::class, 'index', $components),
            'api/panel/dashboard' => $this->isAdmin()->loadControllerAndAction(PanelDashboardController::class, 'index', $components),
            'api/panel/logout' => $this->isAdmin()->loadControllerAndAction(PanelLogoutController::class, 'logout', $components),
            default => $this->loadControllerAndAction(MainController::class, 'index', $components)
        };
    }

    private function postRequests($url): void
    {
        match ($url) {
            'api/user/signup' => $this->loadControllerAndAction(SignupController::class, 'create', $this->request()),
            'api/user/login' => $this->loadControllerAndAction(UserLoginController::class, 'login', $this->request()),
            'api/ticket/register' => $this->isUser()->loadControllerAndAction(RegisterTicketController::class, 'create', $this->request()),
            'api/ticket/answerTicket' => $this->isAdmin()->verifyToken()->loadControllerAndAction(AnswerTicketController::class, 'create', $this->request()),
            'api/panel/login' => $this->loadControllerAndAction(PanelLoginController::class, 'login', $this->request()),
            default => Helper::notFound()
        };
    }

    private function request()
    {
        return json_decode(file_get_contents('php://input'), 1) ?? $_POST;
    }

    private function urlToArray($url, &$id = null): array
    {
        $url = explode('/', $url);
        $id = is_numeric(end($url)) ? '/' . end($url) : null;
        return $url;
    }

    private function checkCsrfToken(&$request)
    {
        return $this;
    }

    private function isAdmin()
    {
        $token = GetDecodedToken::getInstance()->fetch();
        if (!isset($token->userId) || !isset($token->roleId))
            Helper::invalidToken();
        return $this;
    }

    private function isUser()
    {
        $token = GetDecodedToken::getInstance()->fetch();
        if (!isset($token->userId) || !isset($token->roleId))
            Helper::invalidToken();
        return $this;
    }

    private function verifyToken()
    {
        // Token::getInstance()->verify();
        return $this;
    }
}
