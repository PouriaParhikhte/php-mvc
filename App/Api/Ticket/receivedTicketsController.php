<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Received;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\AdminPanelMenu;
use Core\View;
use Exception;

class ReceivedTicketsController extends Controller
{
    public function index()
    {
        try {
            $tickets = Received::tickets();
            $input = [
                'menu' => AdminPanelMenu::panelMenuBuilder(),
                'tickets' => $tickets->result ?? null,
                'pagination' => $tickets->pagination ?? null
            ];
            View::render($_GET['url'], $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
