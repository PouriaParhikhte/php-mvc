<?php

namespace Core\Helpers;

use Core\Helpers\Configs;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Offset;

trait Paging
{
    use Limit, Offset;

    public function paging($perPage): mixed
    {
        $this->checkPageNumber($pageNumber);
        if (!$pageNumber)
            return null;
        $total = $this->getNumRows();
        $start = --$pageNumber * $perPage;
        $result['result'] = $this->limit($start)->offset($perPage)->getResult();
        if (!$result['result'])
            return null;
        $resultLen = count($result);
        if ($total > $resultLen)
            $result['pagination'] = $this->generatePageNumbers($total, $perPage, $pageNumber);
        return $this->toJson($result);
    }

    private function checkPageNumber(&$pageNumber): void
    {
        $urlArray = isset($_GET['url']) ? explode('/', $_GET['url']) : [];
        $end = end($urlArray);
        $pageNumber = is_numeric($end) ? (int)$end : 1;
        if ($pageNumber <= 0)
            $pageNumber = 0;
    }

    private function generatePageNumbers($total, $perPage, $pageNumber): string
    {
        $url = $_GET['url'] ?? Configs::homePageUrl();
        ++$pageNumber;
        $url = rtrim($url, "/$pageNumber");
        $pages = ceil($total / $perPage);
        $pagination = '<ul class="pagination">';
        --$pageNumber;
        for ($i = 0; $i < $pages; ++$i) {
            $active = ($pageNumber === $i) ? 'active' : '';
            $pagination .= '<li><a class="page-link ' . $active . '" href="' . $url . '/' . ++$i . '">' . $i . '</a></li>';
            --$i;
        }
        $pagination .= '</ul>';
        return $pagination;
    }
}
