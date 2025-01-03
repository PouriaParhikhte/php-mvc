<?php

namespace App\Api\Ticket;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\UserPanelMenu;
use Core\View;
use Exception;

class CreateTicketController extends Controller
{
    public function create()
    {
        try {
            $input = [
                'menu' => UserPanelMenu::panelMenuBuilder(),
            ];
            View::render($_GET['url'], $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
