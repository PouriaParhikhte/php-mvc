<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Ticket;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\AdminPanelMenu;
use Core\View;
use Exception;

class AnswerController extends Controller
{
    public function create()
    {
        try {
            $input = [
                'menu' => AdminPanelMenu::panelMenuBuilder(),
                'ticket' => Ticket::fetch(end($this->params)),
                'ticketId' => end($this->params)
            ];
            View::render(Helper::getUrlWithoutQueryString(), $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
