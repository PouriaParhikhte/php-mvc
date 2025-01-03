<?php

namespace Core\Helpers\MysqlFunction;

trait groupConcate
{
    protected $sql;

    public function groupConcate($column, $separator = null)
    {
        $this->sql .= $separator ? " GROUP_CONCAT($column,$separator)" : " GROUP_CONCAT($column)";
        return $this;
    }
}
