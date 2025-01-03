<?php

namespace Core\Helpers\MysqlFunction;

trait SelectConcat
{
    public function selectConcat(array $tableAliases, array $columns, $separator, $alias)
    {
        $t = $tableAliases;
        $this->sql .= "SELECT CONCAT($t[0].$columns[0] $separator $t[0].$columns[1]) AS $alias FROM $this->table";
        return $this;
    }
}
