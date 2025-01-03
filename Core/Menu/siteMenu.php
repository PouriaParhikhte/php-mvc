<?php

namespace Core\Menu;

use Core\crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\Prototype;

class SiteMenu extends Select
{
    protected $table = 'url';
    use Where, OrderBy, Prototype;

    public static function siteMenuBuilder($parentId = 0): string
    {
        $result = (new self)->select()->where(['parentId', $parentId])->orderBy('sort')->getResult();
        self::generateMenuLinks($result, $output);
        return $output;
    }

    private static function generateMenuLinks($queryResult, &$output): void
    {
        $output .= '<ul>';
        foreach ($queryResult as $row) {
            $output .= $row->dropdown ? '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a>' .
                self::siteMenuBuilder($row->urlId) . '</li>' :
                '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a></li>';
        }
        $output .= '</ul>';
    }
}
