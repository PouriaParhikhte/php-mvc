<?php

namespace Core\Helpers\mysqlClause;

use Core\Helpers\Helper;

trait Where
{
    protected $sql;

    public function where(array $where = [])
    {
        $this->sql .= !\strpos($this->sql, 'WHERE') ? ' WHERE' : ' AND';
        $this->whereClauseStatement($where);
        return $this;
    }

    private function whereClauseStatement(array $where = []): void
    {
        $arrayLength = \count($where);
        if ($arrayLength === 2) {
            $this->sql .= " `$where[0]` = ?";
            $this->values[] = $where[1];
        } else {
            $i = 0;
            foreach ($where as $item) {
                if ($i !== 2) {
                    $this->sql .= ' ' . $item;
                    ++$i;
                } else {
                    $this->sql .= ' ? ';
                    $this->values[] = $item;
                    $i = 0;
                }
            }
        }
    }

    public function orWhere(array $where = [])
    {
        $this->sql .= (!\strpos($this->sql, 'WHERE')) ? ' WHERE ' : ' OR';
        $this->whereClauseStatement($where);
        return $this;
    }

    public function whereNot(array $where = [])
    {
        $this->sql .= (!\strpos($this->sql, 'WHERE')) ? ' WHERE NOT' : ' AND NOT';
        $this->whereClauseStatement($where);
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $placeholder = Helper::replaceArrayValuesWithPlaceholder($values);
        $placeholder = Helper::arrayToString($placeholder, ',');
        $this->sql .= " WHERE $column IN ($placeholder)";
        $this->values = array_values($values);
        return $this;
    }

    public function whereIsNull($column)
    {
        $this->sql .= (!\strpos($this->sql, 'WHERE')) ? " WHERE `$column` IS NULL" : " AND `$column` IS NULL";
        return $this;
    }

    public function whereIsNotNull($column)
    {
        $this->sql .= (!\strpos($this->sql, 'WHERE')) ? " WHERE `$column` IS NOT NULL" : " AND `$column` IS NOT NULL";
        return $this;
    }

    public function whereBetween($value, $min, $max)
    {
        $this->sql .= (!\strpos($this->sql, 'WHERE')) ? ' WHERE ' : '';
        $this->sql .= " $value BETWEEN $min AND $max";
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

        $this->sql .= " WHERE $column LIKE '$value'";
        return $this;
    }

    public function whereNotLike($column, $value, $wildcard = '0 = start%, 1 = %end')
    {
        $value = $wildcard ? '%' . $value : $value . '%';
        $this->sql .= " WHERE $column NOT LIKE '$value'";
        return $this;
    }
}
