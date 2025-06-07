<?php

namespace Core\Menu;

use Core\crud\Select;

class SiteMenu extends Select
{
    protected $table = 'url';

    public static function siteMenuBuilder($parent_id = 0): string
    {
        $result = (new self)->select()->where('parentId', $parent_id)->orderBy('sort')->fetchResult();
        ob_start();
        echo '<ul>';
        foreach ($result as $link) {
            echo '<li>';
            if ($link->dropDown) {
                echo "<label>$link->persianUrl</label>";
                echo self::siteMenuBuilder($link->urlId);
            } else
                echo "<a href='" . $link->url . "'>$link->persianUrl</a>";
            echo '</li>';
        }
        echo '</ul>';
        return ob_get_clean();
    }
}
