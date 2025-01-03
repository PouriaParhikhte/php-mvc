<?php

namespace Core\Helpers;

use Core\Crud\Insert;
use Core\Model;

class DatabaseManagementSystem extends Model
{
    private $connection, $databaseName;

    public function __construct()
    {
        $this->connection = mysqli_connect(Configs::hostName(), Configs::username(), Configs::password());
        $this->createDatabase(Configs::database())->setChasrset(Configs::charset())->execute();
    }

    public function chooseDatabase($databaseName = null)
    {
        $this->databaseName = $databaseName;
        $this->connection->select_db($databaseName);
        return $this;
    }

    public function createDatabase($databaseName = null)
    {
        $this->sql = "CREATE DATABASE IF NOT EXISTS $databaseName";
        return $this;
    }

    public function setChasrset($CHARSET = null)
    {
        $this->sql .= " CHARACTER SET $CHARSET";
        return $this;
    }

    public function collate($collate = 'utf8_general_ci')
    {
        $this->sql .= " DEFAULT COLLATE $collate";
        return $this;
    }

    public function dropDatabase($databaseName)
    {
        $this->sql = "DROP DATABASE $databaseName";
        return $this;
    }

    public function dropTable($tableName)
    {
        $this->sql = "DROP TABLE IF EXISTS $tableName";
        return $this;
    }

    public function createTable($tableName)
    {
        $GLOBALS['table'] = $tableName;
        $this->sql = 'CREATE TABLE IF NOT EXISTS';
        $this->sql .= " $this->databaseName.$tableName ";
        return $this;
    }

    public function createTemporaryTable($temporaryTableName)
    {
        $GLOBALS['table'] = $temporaryTableName;
        $this->sql = "CREATE TEMPORARY TABLE $temporaryTableName";
        return $this;
    }

    public function asExistsTable($existsTableName)
    {
        $this->sql .= " (SELECT * FROM $existsTableName)";
        return $this;
    }

    public function id()
    {
        $this->bigInt()->unsigned()->autoIncrement()->primaryKey();
        if (\strpos($this->sql, '(,'))
            $this->sql = \str_replace('(,', '(', $this->sql);
        return $this;
    }

    public function primaryKey()
    {
        $this->sql .= " PRIMARY KEY";
        return $this;
    }

    public function autoIncrement()
    {
        $this->sql .= " AUTO_INCREMENT";
        return $this;
    }

    public function varchar($len = 65535)
    {
        $this->sql .= " VARCHAR($len)";
        return $this;
    }

    public function boolean()
    {
        $this->sql .= " BOOLEAN";
        return $this;
    }

    public function tinyInt()
    {
        $this->sql .= " TINYINT";
        return $this;
    }

    public function smallInt()
    {
        $this->sql .= " SMALLINT";
        return $this;
    }

    public function mediumInt()
    {
        $this->sql .= " MEDIUMINT";
        return $this;
    }

    public function int()
    {
        $this->sql .= " INT";
        return $this;
    }

    public function bigInt()
    {
        $this->sql .= " BIGINT";
        return $this;
    }

    public function json()
    {
        $this->sql .= " JSON";
        return $this;
    }

    public function nullable()
    {
        $this->sql .= ' NULL';
        return $this;
    }

    public function notNull()
    {
        $this->sql .= ' NOT NULL';
        return $this;
    }

    public function innoDb()
    {
        $this->sql .= ') ENGINE = innoDB';
        return $this;
    }

    public function myIsam()
    {
        $this->sql .= ') ENGINE = MyISAM';
        return $this;
    }

    public function memory()
    {
        $this->sql .= ' ENGINE = MEMORY';
        return $this;
    }

    public function timestamp()
    {
        $this->sql .= ' TIMESTAMP';
        return $this;
    }

    public function dateTime()
    {
        $this->sql .= " DATETIME";
        return $this;
    }

    public function time()
    {
        $this->sql .= " time";
        return $this;
    }

    public function timestamps()
    {
        $this->column('createdAt')->timestamp()->notNull()->default()->currentTimestamp();
        $this->column('updatedAt')->timestamp()->notNull()->default()->currentTimestamp()->onUpdate()->currentTimestamp();

        if (\strpos($this->sql, '(,'))
            $this->sql = \str_replace('(,', '(', $this->sql);
        return $this;
    }

    public function unixTimestamp()
    {
        $this->sql .= " UNIX_TIMESTAMP(CURRENT_TIMESTAMP)";
        return $this;
    }

    public function currentTimestamp()
    {
        $this->sql .= " CURRENT_TIMESTAMP";
        return $this;
    }

    public function execute(): bool
    {
        $this->sql = \rtrim($this->sql, ',');
        $result = $this->connection->query($this->sql);
        if (!$result) {
            $message = $this->connection->error;
            throw new \Exception($message);
        }
        return $result;
    }

    public function unsigned()
    {
        $this->sql .= ' UNSIGNED';
        return $this;
    }

    public function alterTable($tableName)
    {
        $GLOBALS['table'] = $tableName;
        $this->sql = "ALTER TABLE $tableName";
        return $this;
    }

    public function column($columnName)
    {
        $this->sql = trim($this->sql);
        $pos = strpos($this->sql, $GLOBALS['table']);
        $this->sql .= (substr($this->sql, $pos) !== $GLOBALS['table']) ? ", $columnName" : " ($columnName";
        return $this;
    }

    public function addColumn($columnName)
    {
        $this->sql .= !\strpos($this->sql, 'ADD COLUMN') ? " ADD COLUMN $columnName" : ",$columnName";
        return $this;
    }

    public function modifyColumn($columnName)
    {
        $this->sql .= !\strpos($this->sql, 'MODIFY') ? " MODIFY $columnName" : ", MODIFY $columnName";
        return $this;
    }

    public function renameColumn($originalName, $newName)
    {
        $this->sql .= !\strpos($this->sql, 'CHANGE COLUMN') ? " RENAME COLUMN $originalName TO $newName" : ", RENAME COLUMN $originalName TO $newName";
        return $this;
    }

    public function dropColumn($columnName)
    {
        $this->sql .= !\strpos($this->sql, 'DROP COLUMN') ? " DROP COLUMN $columnName" : ", DROP COLUMN $columnName";
        return $this;
    }

    public function dropIndex($indexName)
    {
        $this->sql .= "DROP INDEX $indexName";
        return $this;
    }

    public function renameTable($newTableName)
    {
        $this->sql .= " RENAME TO $newTableName";
        return $this;
    }

    public function after($columnName = null)
    {
        $this->sql .= " AFTER $columnName";
        return $this;
    }

    public function default($defaultValue = null)
    {
        $this->sql .= (isset($defaultValue)) ? " DEFAULT '$defaultValue'" : ' DEFAULT';
        return $this;
    }

    public function foreignKey($keyName, $columnName)
    {
        $this->sql .= ", CONSTRAINT $keyName FOREIGN KEY ($columnName)";
        return $this;
    }

    public function addForeignKey($columnName)
    {
        $this->sql .= " ADD FOREIGN KEY ($columnName)";
        return $this;
    }

    public function refrences($parentTable, array $columnName)
    {
        $columnName = \implode(',', $columnName);
        $this->sql .= " REFERENCES $parentTable($columnName)";
        return $this;
    }

    public function onUpdate()
    {
        $this->sql .= ' ON UPDATE ';
        return $this;
    }

    public function onDelete()
    {
        $this->sql .= ' ON DELETE ';
        return $this;
    }

    public function index($columns = [], $indexName = null)
    {
        $columns = \implode(',', $columns);
        $this->sql .= ", INDEX $indexName ($columns)";
        return $this;
    }

    public function unique($columns = [], $indexName = null)
    {
        $columns = \implode(',', $columns);
        $this->sql .= ", UNIQUE $indexName ($columns)";
        return $this;
    }

    public function createIndex($indexName, $tableName, $columnName)
    {
        $this->sql .= "CREATE INDEX $indexName ON $tableName($columnName)";
        return $this;
    }

    protected function addUserRole(): void
    {
        $roles = ['admin', 'user', 'visitor'];
        $this->sql = "SELECT `roleName` FROM `userRole`";
        $result = $this->connection->query($this->sql)->fetch_all(1);
        $roleName = array_column($result, 'roleName');

        foreach ($roles as $key => $role) {
            if (!in_array($role, $roleName)) {
                $sql = "INSERT INTO `userRole` (roleName) VALUES ('$role')";
                $this->connection->query($sql);
            }
        }
    }

    protected function addUser()
    {
        $password = md5('12345' . time());
        $user = ['username' => 'مدیر', 'password' => $password, 'roleId' => 1];
        $insert = new Insert;
        $insert->__invoke('user')->insert($user)->getResult();
    }

    protected function addUrl(): void
    {
        $urls = [
            ['url' => 'home', 'persianUrl' => 'خانه', 'dropdown' => '0', 'parentId' => 0],
            ['url' => 'products', 'persianUrl' => 'محصولات', 'dropdown' => '1', 'parentId' => 0],
            ['url' => 'product1', 'persianUrl' => 'محصول1', 'dropdown' => '0', 'parentId' => 2],
        ];
        foreach ($urls as $url) {
            $sql = "SELECT * FROM `url` WHERE `url` <=> '$url[url]'";
            if (!$this->connection->query($sql)->num_rows) {
                $sql = "INSERT INTO `url` (`url`,`persianUrl`,`dropdown`,`parentId`) VALUES ('$url[url]','$url[persianUrl]','$url[dropdown]','$url[parentId]')";
                $this->connection->query($sql);
            }
        }
    }

    protected function addUserPanelUrl(): void
    {
        $urls = [
            ['url' => 'api/user/dashboard', 'persianUrl' => 'داشبورد', 'dropdown' => '0', 'parentId' => 0],
            ['url' => 'ticket', 'persianUrl' => 'تیکت پشنیبانی', 'dropdown' => '1', 'parentId' => 0],
            ['url' => 'api/ticket/create', 'persianUrl' => 'ایجاد تیکت', 'parentId' => 2],
            ['url' => 'api/ticket/index', 'persianUrl' => 'تیکت های ارسال شده', 'parentId' => 2],
            ['url' => 'api/user/logout', 'persianUrl' => 'خروج از حساب کلربری'],
        ];

        foreach ($urls as $url) {
            $sql = "SELECT * FROM `userPanelUrl` WHERE `url` <=> '$url[url]'";
            if (!$this->connection->query($sql)->num_rows) {
                $sql = "INSERT INTO `userPanelUrl` (`url`,`persianUrl`,`dropdown`,`parentId`) VALUES ('$url[url]','$url[persianUrl]','$url[dropdown]','$url[parentId]')";
                $this->connection->query($sql);
            }
        }
    }

    protected function addAdminPanelUrl(): void
    {
        $urls = [
            ['url' => 'api/panel/dashboard', 'persianUrl' => 'داشبورد'],
            ['url' => 'api/ticket/received', 'persianUrl' => 'تیکت های دریافتی'],
            ['url' => 'api/panel/logout', 'persianUrl' => 'خروج از حساب کلربری'],
        ];

        foreach ($urls as $url) {
            $sql = "SELECT * FROM `adminPanelUrl` WHERE `url` <=> '$url[url]'";
            if (!$this->connection->query($sql)->num_rows) {
                $sql = "INSERT INTO `adminPanelUrl` (`url`,`persianUrl`,`dropdown`,`parentId`) VALUES ('$url[url]','$url[persianUrl]','$url[dropdown]','$url[parentId]')";
                $this->connection->query($sql);
            }
        }
    }

    public function addContent(): void
    {
        $content = [
            ['title' => 'welcome', 'content' => 'some text some text some text', 'urlId' => '1'],
            ['title' => 'post1', 'content' => 'post1', 'urlId' => '1'],
            ['title' => 'post2', 'content' => 'post2', 'urlId' => '1'],
            ['title' => 'post3', 'content' => 'post3', 'urlId' => '1'],
            ['title' => 'post4', 'content' => 'post4', 'urlId' => '1'],
        ];
        $this->sql = "SELECT `title`,`content` FROM `content`";
        if (!$this->connection->query($this->sql)->num_rows) {
            foreach ($content as $post) {
                $sql = "INSERT INTO `content` (`title`,`content`,`urlId`) VALUES ('$post[title]','$post[content]','$post[urlId]')";
                $this->connection->query($sql);
            }
        }
    }
}
