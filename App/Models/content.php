<?php

namespace App\Models;

use Core\crud\Select;
use Core\Helper;

class Content extends Select
{
    protected $table = 'post';

    private function getContent($url, $toArray = false)
    {
        return $this->select()->innerJoin('url', 'urlId')->where('url', $url[0])->paging(SETTINGS->PERPAGE, $toArray);
    }

    public function showPosts($url)
    {
        if ((count($url) >= 2 && !is_numeric(end($url))) || null === $posts = $this->getContent($url))
            Helper::notFound();
        ob_start();
        foreach ($posts->result as $post) {
            echo "<h2>$post->postTitle</h2><p>$post->postText</p>";
        }
        echo $posts->pagination ?? null;
        return ob_get_clean();
    }
}
