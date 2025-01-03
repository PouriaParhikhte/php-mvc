<?php

namespace Core\Menu;

use Core\crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;

class UserPanelMenu extends Select
{
    protected $table = 'userPanelUrl';
    use Where, OrderBy;

    public static function panelMenuBuilder($parentId = 0): string
    {
        $result = (new self)->select()->where(['parentId', $parentId])->orderBy('sort')->getResult();
        self::generatePanelMenuLinks($result, $output);
        return $output;
    }

    private static function generatePanelMenuLinks(array $queryResult, &$output): void
    {
        $output = '<ul>';
        foreach ($queryResult as $row) {
            $output .= ($row->dropdown) ?
                '<i class="caretDown"></i><li><label for="link' . $row->urlId . '" >' . $row->persianUrl . '</label>
                <input type="checkbox" name="link" id="link' . $row->urlId . '">' . self::panelMenuBuilder($row->urlId) . '</li>'
                :
                '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a></li>';
        }
        $output .= '</ul > ';
    }
}
