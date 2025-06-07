<?php

namespace Core\Helpers\Database;

use Core\Model;
use stdClass;

class DatabaseManagementSystem extends Model
{
    private $databaseName, $columnName, $columnNameWithPrefix;

    public function __construct()
    {
        $this->sql = new stdClass;
    }

    public function createDatabase($databaseName)
    {
        $this->sql->query = "CREATE DATABASE IF NOT EXISTS $databaseName";
        return $this;
    }

    public function chooseDatabase($databaseName = null)
    {
        $this->databaseName = $databaseName;
        return $this;
    }

    public function setChasrset($CHARSET = null)
    {
        $this->sql->query .= " CHARACTER SET $CHARSET";
        return $this;
    }

    public function collate($collate = 'utf8_general_ci')
    {
        $this->sql->query .= " DEFAULT COLLATE $collate";
        return $this;
    }

    public function dropDatabase($databaseName)
    {
        $this->sql->query = "DROP DATABASE $databaseName";
        return $this;
    }

    public function dropTable($tableName)
    {
        $this->sql->query = "DROP TABLE IF EXISTS $tableName";
        return $this;
    }

    public function createTable($tableName)
    {
        $GLOBALS['table'] = $tableName;
        $tableName = SETTINGS->DATABASE . ".$tableName";
        $this->sql->query = 'CREATE TABLE IF NOT EXISTS';
        $this->sql->query .= " $tableName ";
        return $this;
    }

    public function createTemporaryTable($temporaryTableName)
    {
        $GLOBALS['table'] = $temporaryTableName;
        $this->sql->query = "CREATE TEMPORARY TABLE $temporaryTableName";
        return $this;
    }

    public function asExistsTable($existsTableName)
    {
        $this->sql->query .= " (SELECT * FROM $existsTableName)";
        return $this;
    }

    public function id()
    {
        $this->bigInt()->unsigned()->autoIncrement()->primaryKey();
        if (strpos($this->sql->query, '(,'))
            $this->sql->query = str_replace('(,', '(', $this->sql->query);
        return $this;
    }

    public function primaryKey()
    {
        $this->sql->query .= " PRIMARY KEY";
        return $this;
    }

    public function autoIncrement()
    {
        $this->sql->query .= " AUTO_INCREMENT";
        return $this;
    }

    public function varchar($len = 65535)
    {
        $this->sql->query .= " VARCHAR($len)";
        return $this;
    }

    public function boolean()
    {
        $this->sql->query .= " BOOLEAN";
        return $this;
    }

    public function tinyInt()
    {
        $this->sql->query .= " TINYINT";
        return $this;
    }

    public function smallInt()
    {
        $this->sql->query .= " SMALLINT";
        return $this;
    }

    public function mediumInt()
    {
        $this->sql->query .= " MEDIUMINT";
        return $this;
    }

    public function int()
    {
        $this->sql->query .= " INT";
        return $this;
    }

    public function bigInt()
    {
        $this->sql->query .= " BIGINT";
        return $this;
    }

    public function json()
    {
        $this->sql->query .= " JSON";
        return $this;
    }

    public function nullable()
    {
        $this->sql->query .= ' NULL';
        return $this;
    }

    public function notNull()
    {
        $this->sql->query .= ' NOT NULL';
        return $this;
    }

    public function innoDb()
    {
        $this->sql->query .= ') ENGINE = innoDB';
        return $this;
    }

    public function myIsam()
    {
        $this->sql->query .= ') ENGINE = MyISAM';
        return $this;
    }

    public function memory()
    {
        $this->sql->query .= ') ENGINE = MEMORY';
        return $this;
    }

    public function timestamp()
    {
        $this->sql->query .= ' TIMESTAMP';
        return $this;
    }

    public function dateTime()
    {
        $this->sql->query .= " DATETIME";
        return $this;
    }

    public function time()
    {
        $this->sql->query .= " time";
        return $this;
    }

    public function timestamps()
    {
        $this->column('createdAt')->timestamp()->notNull()->default()->currentTimestamp();
        $this->column('updatedAt')->timestamp()->notNull()->default()->currentTimestamp()->onUpdate()->currentTimestamp();

        if (\strpos($this->sql->query, '(,'))
            $this->sql->query = \str_replace('(,', '(', $this->sql->query);
        return $this;
    }

    public function unixTimestamp()
    {
        $this->sql->query .= " UNIX_TIMESTAMP(CURRENT_TIMESTAMP)";
        return $this;
    }

    public function currentTimestamp()
    {
        $this->sql->query .= " CURRENT_TIMESTAMP";
        return $this;
    }

    public function executeSqlQuery()
    {
        $this->result();
    }

    public function unsigned()
    {
        $this->sql->query .= ' UNSIGNED';
        return $this;
    }

    public function alterTable($tableName)
    {
        $GLOBALS['table'] = $tableName;
        $this->sql->query = "ALTER TABLE $tableName";
        return $this;
    }

    public function column($columnName, string $prefix = '')
    {
        if ($prefix !== '')
            $this->columnNameWithPrefix[] = "$prefix$columnName";
        $this->columnName[] = "`$columnName`";
        $this->sql->query = trim($this->sql->query);
        $pos = strpos($this->sql->query, $GLOBALS['table']);
        $this->sql->query .= (substr($this->sql->query, $pos) !==
            $GLOBALS['table']) ? ", `$columnName`" : " (`$columnName`";
        return $this;
    }

    public function addColumn($columnName)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'ADD COLUMN')
            ? " ADD COLUMN $columnName" : ",$columnName";
        return $this;
    }

    public function modifyColumn($columnName)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'MODIFY')
            ? " MODIFY $columnName" : ", MODIFY $columnName";
        return $this;
    }

    public function renameColumn($originalName, $newName)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'CHANGE COLUMN')
            ? " RENAME COLUMN $originalName TO $newName"
            : ", RENAME COLUMN $originalName TO $newName";
        return $this;
    }

    public function dropColumn($columnName)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'DROP COLUMN')
            ? " DROP COLUMN $columnName" : ", DROP COLUMN $columnName";
        return $this;
    }

    public function dropIndex($indexName)
    {
        $this->sql->query .= "DROP INDEX $indexName";
        return $this;
    }

    public function renameTable($newTableName)
    {
        $this->sql->query .= " RENAME TO $newTableName";
        return $this;
    }

    public function after($columnName = null)
    {
        $this->sql->query .= " AFTER $columnName";
        return $this;
    }

    public function default($defaultValue = null)
    {
        $this->sql->query .= (isset($defaultValue))
            ? " DEFAULT '$defaultValue'" : ' DEFAULT';
        return $this;
    }

    public function foreignKey($keyName, $columnName)
    {
        $this->sql->query .= ", CONSTRAINT $keyName FOREIGN KEY ($columnName)";
        return $this;
    }

    public function addForeignKey($columnName)
    {
        $this->sql->query .= " ADD FOREIGN KEY ($columnName)";
        return $this;
    }

    public function refrences($parentTable, array $columnName)
    {
        $columnName = \implode(',', $columnName);
        $this->sql->query .= " REFERENCES $parentTable($columnName)";
        return $this;
    }

    public function onUpdate()
    {
        $this->sql->query .= ' ON UPDATE ';
        return $this;
    }

    public function onDelete()
    {
        $this->sql->query .= ' ON DELETE ';
        return $this;
    }

    public function index($columns = [], $indexName = null)
    {
        $columns = \implode(',', $columns);
        $this->sql->query .= ", INDEX $indexName ($columns)";
        return $this;
    }

    public function unique($columns = [], $indexName = null)
    {
        $columns = \implode(',', $columns);
        $this->sql->query .= ", UNIQUE $indexName ($columns)";
        return $this;
    }

    public function createIndex($indexName, $tableName, $columnName)
    {
        $this->sql->query .= "CREATE INDEX $indexName ON 
        $tableName($columnName)";
        return $this;
    }
}
