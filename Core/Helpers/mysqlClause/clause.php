<?php

namespace Core\Helpers\mysqlClause;

use Core\Helper;
use Core\Model;

class Clause extends Model
{
    public function as(string $name)
    {
        $this->sql->query .= " AS $name";
        return $this;
    }

    public function checkConstraint()
    {
        $this->sql->query .= " CHECK CONSTAINT ALL";
        return $this;
    }

    public function concatOnUpdate($columnName,  $input)
    {
        $this->sql->query .= " ON DUPLICATE KEY UPDATE `$columnName` = CONCAT('$input',$columnName)";
        return $this;
    }

    public function dateSub($intervalValue, $interval)
    {
        $this->sql->query .= (strpos($this->sql->query, 'WHERE')) ? ' AND' : '';
        $this->sql->query .= " updatedAt < DATE_SUB(now(), INTERVAL $intervalValue $interval)";
        return $this;
    }

    public function disableForeignKeyCheck()
    {
        $this->sql->query = "SET FOREIGN_KEY_CHECKS=0;";
        return $this;
    }

    public function enableForeignKeyCheck()
    {
        $this->sql->query = "SET FOREIGN_KEY_CHECKS=1;";
        return $this;
    }

    public function escape($scapeClause)
    {
        $this->sql->query .= " ESCAPE '$scapeClause'";
        return $this;
    }

    public function groupBy($column)
    {
        $this->sql->query .= " GROUP BY $column";
        return $this;
    }

    public function having($column, $operator, $value)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'HAVING') ? ' HAVING' : ' AND';
        $this->sql->query .= " $column $operator $value";
        return $this;
    }

    public function innerJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql->query .= " INNER JOIN";
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }

    public function rightJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql->query .= ' RIGHT JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }

    public function leftJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql->query .= ' LEFT JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }

    public function crossJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql->query .= " t1 CROSS JOIN";
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }

    public function leftOuterJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql->query .= ' LEFT OUTER JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }

    private function joinCondition($tableName, $foreignKey = null, $ownerKey = null)
    {
        $this->sql->query .= ($ownerKey !== null) ? " `$tableName` ON $this->table.$foreignKey = $tableName.$ownerKey" : " `$tableName` USING($foreignKey)";
        return $this;
    }

    public function lastInsertId(): mixed
    {
        $this->sql->query = "SELECT LAST_INSERT_ID() FROM `$this->table`";
        return $this->getResult();
    }

    public function limit($value, $offset = null)
    {
        $this->sql->query .= " LIMIT '$value'";
        $this->values[] = $value;
        if ($offset !== null) {
            $this->sql->query .= ",'$offset'";
            $this->values[] = $offset;
        }
        return $this;
    }

    public function first(int $toArray = 0): mixed
    {
        return $this->limit(1)->getResult($toArray)[0] ?? null;
    }

    public function noCheckConstraint()
    {
        $this->sql->query .= " NOCHECK CONSTAINT ALL";
        return $this;
    }

    public function orderBy($column, $sort = 'ASC')
    {
        $this->sql->query .= " ORDER BY $column $sort";
        return $this;
    }

    public function selectDistinct(array $columns)
    {
        $columns = \implode(',', $columns);
        $this->sql->query = "SELECT DISTINCT $columns FROM $this->table";
        return $this;
    }

    public function union(): void
    {
        $this->sql->query .= " UNION ";
    }

    public function where(string $column, string $value, $operator = '=')
    {
        $this->sql->query .= (str_contains($this->sql->query, 'WHERE') ? " AND" : " WHERE") . " $column $operator '$value'";
        $this->values[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $value, $operator = '=')
    {
        $this->sql->query .= (str_contains($this->sql->query, 'WHERE') ? " OR" : " WHERE") . " $column $operator '$value'";
        $this->values[] = $value;
        return $this;
    }

    public function whereNot(string $column, string $value, $operator = '=')
    {
        $this->sql->query .= (str_contains($this->sql->query, 'WHERE') ? " AND NOT" : " WHERE") . " $column $operator '$value'";
        $this->values[] = $value;
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $placeholder = Helper::replaceArrayValuesWithPlaceholder($values);
        $placeholder = Helper::arrayToString($placeholder, ',');
        $this->sql->query .= " WHERE $column IN ($placeholder)";
        $this->values = array_values($values);
        return $this;
    }

    public function whereIsNull($column)
    {
        $this->sql->query .= (!\strpos($this->sql->query, 'WHERE')) ? " WHERE `$column` IS NULL" : " AND `$column` IS NULL";
        return $this;
    }

    public function whereIsNotNull($column)
    {
        $this->sql->query .= (!\strpos($this->sql->query, 'WHERE')) ? " WHERE `$column` IS NOT NULL" : " AND `$column` IS NOT NULL";
        return $this;
    }

    public function whereBetween($value, $min, $max)
    {
        $this->sql->query .= (!\strpos($this->sql->query, 'WHERE')) ? ' WHERE ' : '';
        $this->sql->query .= " '$value' BETWEEN '$min' AND '$max'";
        $this->values[] = $value;
        $this->values[] = $min;
        $this->values[] = $max;
        return $this;
    }

    //wildcard => 0 = startWith%, 1 = %endWith, 2 = %contain%, 3 = _
    public function whereLike($column, $value, $wildcard = 0)
    {
        if (!$wildcard)
            $value .= '%';
        elseif ($wildcard === 1)
            $value = '%' . $value;
        else
            $value = '%' . $value . '%';
        $this->sql->query .= " WHERE $column LIKE '$value'";
        $this->values[] = $column;
        $this->values[] = $value;
        return $this;
    }

    public function whereNotLike($column, $value, $wildcard = '0 = start%, 1 = %end')
    {
        $value = $wildcard ? '%' . $value : $value . '%';
        $this->sql->query .= " WHERE $column NOT LIKE '$value'";
        $this->values[] = $column;
        $this->values[] = $value;
        return $this;
    }

    public function paging($perPage, $toArray = false): mixed
    {
        if (!$pageNumber = $this->checkPageNumber())
            return null;
        $total = $this->getNumRows();
        $start = --$pageNumber * $perPage;
        $result['result'] = $this->limit($start, $perPage)->getResult();
        if (!$result['result'])
            return null;
        $resultLen = count($result['result']);
        if ($total > $resultLen)
            $result['pagination'] = $this->generatePageNumbers($total, $perPage, $pageNumber);
        return Helper::toJson($result, $toArray);
    }

    private function checkPageNumber()
    {
        if (isset($_GET['url']) && $url = rtrim($_GET['url'], '/'))
            $urlArray = explode('/', $url);
        $end = !empty($urlArray) ? end($urlArray) : 1;
        $pageNumber = is_numeric($end) ? (int)$end : 1;
        return ($pageNumber <= 0) ? 0 : $pageNumber;
    }

    private function generatePageNumbers($total, $perPage, $pageNumber): string
    {
        $baseUrl = json_decode(file_get_contents('php://input')) ?? $_GET['url'] ?? SETTINGS->HOMEPAGEURL;
        $baseUrl = rtrim($baseUrl, '/');
        if (is_numeric(basename($baseUrl)))
            $baseUrl = dirname($baseUrl);
        $pages = ceil($total / $perPage);
        ob_start();
        echo '<ul class="pagination">';
        for ($i = 1; $i <= $pages; $i++) {
            $active = ($pageNumber === $i - 1) ? 'active' : '';
            echo '<li><a class="page-link ' . $active . '" href="' . $baseUrl . '/' . $i . '">' . $i . '</a></li>';
        }
        echo '</ul>';
        return ob_get_clean();
    }

    public function groupConcate($column, $separator = null)
    {
        $this->sql->query .= $separator ? " GROUP_CONCAT($column,$separator)" : " GROUP_CONCAT($column)";
        return $this;
    }

    public function selectConcat(array $tableAliases, array $columns, $separator, $alias)
    {
        $t = $tableAliases;
        $this->sql->query .= "SELECT CONCAT($t[0].$columns[0] $separator 
        $t[0].$columns[1]) AS $alias FROM $this->table";
        return $this;
    }
}
