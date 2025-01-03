<?php

namespace App\Controllers;

use Core\Helpers\Helper;
use App\Models\Content;
use Core\Controller;
use Core\Menu\SiteMenu;
use Core\View;
use Exception;

class MainController extends Controller
{
    public function index(Content $content)
    {
        try {
            $page = $content->getContent($this->params[0]) ?? Helper::notFound();
            $input = [
                'content' => $page->result,
                'pagination' => $page->pagination ?? null,
                'menu' => SiteMenu::siteMenuBuilder()
            ];
            View::render($this->params[0], $input);
        } catch (Exception $exception) {
            exit($exception->getMessage());
        }
    }
}
