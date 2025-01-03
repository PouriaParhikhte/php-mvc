<?php

namespace App\Api\Panel;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Menu\AdminPanelMenu;
use Core\View;
use Exception;

class PanelDashboardController extends Controller
{
    public static function index()
    {
        try {
            $input = [
                'menu' => AdminPanelMenu::panelMenuBuilder()
            ];
            View::render('Api/Panel/dashboard', $input);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
