<?php

namespace App\Controllers;

use App\Models\Content;
use App\Models\CustomerAccountForm;
use App\Models\TemporaryCodeForm;
use Core\Controller;
use Core\Helper;
use Core\Helpers\Cache;
use Core\Helpers\Csrf;
use Core\Menu\SiteMenu;
use Exception;
use stdClass;

class MainController extends Controller
{
    public function index()
    {
        try {
            $data->token = $this->pageMenuAndContent($data)->getHtmlElements($data->elements)->token()->getToken();
            Helper::response($data->posts)->render($this->params[0], $data);
        } catch (Exception) {
            DatabaseController::create();
        }
    }

    private function pageMenuAndContent(&$output)
    {
        if (null === $output = Cache::getInstance()->get('data')) {
            $output = new stdClass;
            $output->menu = '<nav>' . SiteMenu::siteMenuBuilder() . '</nav>';
            $output->posts = '<div id="posts">' . Content::getInstance()->showPosts($this->params) . '</div>';
            Cache::getInstance()->set('data', json_encode($output));
        }
        return $this;
    }

    private function getHtmlElements(&$output)
    {
        if (!Helper::token()->isLogged()) {
            $output = !isset(Helper::token()->getToken()->temporaryCode) ? CustomerAccountForm::fetch() : TemporaryCodeForm::fetch();
            if (!Helper::isAjax())
                Csrf::addTokenFieldintoFormElement($output->tags);
        }
        return Helper::getInstance();
    }
}
