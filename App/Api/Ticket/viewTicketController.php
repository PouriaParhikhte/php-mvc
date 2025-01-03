<?php

namespace App\Api\Ticket;

use App\Models\Ticket\ViewTicket;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\UserPanelMenu;
use Core\Helpers\Token;
use Core\Helpers\Token\fetchValueFromToken;
use Core\View;
use Exception;

class ViewTicketController extends Controller
{
    public function index()
    {
        try {
            $input = [
                'menu' => UserPanelMenu::panelMenuBuilder(),
                'ticket' => ViewTicket::fetch(end($this->params), fetchValueFromToken::getInstance()->fetch('userId')) ?? Helper::notFound()
            ];
            View::render(Helper::getUrlWithoutQueryString(), $input);
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }
}
