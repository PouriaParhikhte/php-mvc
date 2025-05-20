<?php

namespace App\Models;

use Core\crud\Select;

class Content extends Select
{
    protected $table = 'post';

    private function getContent($url, $toArray = false)
    {
        return $this->select()->innerJoin('url', 'urlId')->where('url', $url)->paging(SETTINGS->PERPAGE, $toArray);
    }

    public function showPosts($url)
    {
        if ((count($url) === 2 && !is_numeric(end($url))) || count($url) > 2 || null === $posts = $this->getContent($url[0]))
            $this->notFound();
        ob_start();
        foreach ($posts->result as $post) {
            echo '<h2>' . $post->postTitle . '</h2>' . '<p>' . $post->postText . '</p>';
        }
        echo $posts->pagination ?? null;
        return ob_get_clean();
    }
}
