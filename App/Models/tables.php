<?php

namespace App\Models;

use Core\Helpers\DatabaseManagementSystem;

class Tables extends DatabaseManagementSystem
{
    public function userRole()
    {
        $this->createTable('userRole')
            ->column('roleId')->id()
            ->column('roleName')->varchar(255)->notNull()
            ->innoDb()
            ->execute();
        $this->addUserRole();
    }

    public function user()
    {
        $this->createTable('user')
            ->column('userId')->id()
            ->column('username')->varchar(255)->notNull()
            ->column('password')->varchar(255)->notNull()
            ->column('status')->boolean()->notNull()->default('1')
            ->column('roleId')->bigInt()->unsigned()->notNull()->default(2)->foreignKey('roleIdKey', 'roleId')->refrences('userRole', ['roleId'])
            ->timestamps()
            ->unique(['username'], 'usernameIdx')
            ->innoDb()
            ->execute();
        $this->addUser();
    }

    public function url()
    {
        $this->createTable('url')
            ->column('urlId')->id()
            ->column('url')->varchar(255)->notNull()
            ->column('persianUrl')->varchar(255)->notNull()
            ->column('dropdown')->tinyInt()->unsigned()->default('0')
            ->column('parentId')->bigInt()->unsigned()->default(0)
            ->column('sort')->tinyInt()->unsigned()->notNull()
            ->index(['parentId'], 'parentIdIdx')
            ->innoDb()
            ->execute();
        $this->addUrl();
    }

    public function content()
    {
        $this->createTable('content')
            ->column('contentId')->id()
            ->column('title')->varchar(225)->notNull()
            ->column('content')->varchar()->notNull()
            ->column('urlId')->bigInt()->unsigned()->notNull()->foreignKey('urlIdKey', 'urlId')->refrences('url', ['urlId'])
            ->innoDb()
            ->execute();
        $this->addContent();
    }

    public function userPanelUrl()
    {
        $this->createTable('userPanelUrl')
            ->column('urlId')->id()
            ->column('url')->varchar(255)->notNull()
            ->column('persianUrl')->varchar(255)->notNull()
            ->column('dropdown')->tinyInt()->unsigned()->default('0')
            ->column('parentId')->bigInt()->unsigned()->default(0)
            ->column('sort')->tinyInt()->unsigned()->notNull()
            ->index(['parentId'], 'parentIdIdx')
            ->innoDb()
            ->execute();
        $this->addUserPanelUrl();
    }

    public function adminPanelUrl()
    {
        $this->createTable('adminPanelUrl')
            ->column('urlId')->id()
            ->column('url')->varchar(255)->notNull()
            ->column('persianUrl')->varchar(255)->notNull()
            ->column('dropdown')->tinyInt()->unsigned()->default('0')
            ->column('parentId')->bigInt()->unsigned()->default(0)
            ->column('sort')->tinyInt()->unsigned()->notNull()
            ->index(['parentId'], 'parentIdIdx')
            ->innoDb()
            ->execute();
        $this->addAdminPanelUrl();
    }

    public function ticket()
    {
        $this->createTable('ticket')
            ->column('ticketId')->id()
            ->column('ticketTitle')->varchar(255)->notNull()
            ->column('ticketText')->varchar(1500)->notNull()
            ->column('userId')->bigInt()->unsigned()->notNull()->foreignKey('userIdKey', 'userId')->refrences('user', ['userId'])
            ->column('answer')->varchar(255)->nullable()
            ->column('status')->boolean()->default('0')
            ->innoDb()
            ->execute();
    }

    public function session()
    {
        $this->createTable('session')
            ->column('userIp')->varchar(255)->notNull()
            ->column('token')->varchar()->notNull()
            ->unique(['userIp'])
            ->innoDb()
            ->execute();
    }
}
