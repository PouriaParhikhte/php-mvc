<?php

namespace App\Models;

use Core\Helpers\Database\DatabaseManagementSystem;
use Core\Helpers\Database\Seeder;

class Tables extends DatabaseManagementSystem
{
    public function cache()
    {
        $this->createTable('cache')
            ->column('cacheKey')->varchar(255)->notNull()
            ->column('value')->varchar(15000)->notNull()
            ->column('url')->varchar(255)->notNull()
            ->unique(['url'])
            ->memory()
            ->executeSqlQuery();
    }

    public function media()
    {
        $this->createTable('media')
            ->column('mediaId')->id()
            ->column('mediaType')->varchar(50)->notNull() /* image / video ... */
            ->column('mediaUrl')->varchar(1500)->notNull()
            ->innoDb()
            ->executeSqlQuery();
    }

    public function url()
    {
        $this->createTable('url')
            ->column('urlId')->id()
            ->column('url')->varchar(255)->notNull()
            ->column('persianUrl')->varchar(255)->notNull()
            ->column('dropDown')->tinyInt()->unsigned()->default('0')
            ->column('parentId')->bigInt()->unsigned()->default(0)
            ->column('sort')->bigInt()->unsigned()->notNull()
            ->index(['parentId'], 'parentId_idx')
            ->innoDb()
            ->executeSqlQuery();
        Seeder::url();
    }

    public function userRole()
    {
        $this->createTable('user_role')
            ->column('roleId')->id()
            ->column('roleName')->varchar(255)->notNull()
            ->innoDb()
            ->executeSqlQuery();
        Seeder::user_role();
    }

    public function user()
    {
        $this->createTable('user')
            ->column('userId')->id()
            ->column('username')->varchar(255)->notNull()
            ->column('password')->varchar(255)->notNull()
            ->column('accessToken')->varchar(32)->notNull()
            ->column('roleId')->bigInt()->unsigned()->notNull()->default(2)
            ->foreignKey('roleId_key', 'roleId')->refrences('user_role', ['roleId'])
            ->timestamps()
            ->unique(['username'], 'username_idx')
            ->innoDb()
            ->executeSqlQuery();
        Seeder::user();
    }

    public function post()
    {
        $this->createTable('post')
            ->column('postId')->id()
            ->column('postTitle')->varchar()->notNull()
            ->column('postText')->varchar()->notNull()
            ->column('mediaId')->bigInt()->unsigned()
            ->foreignKey('post_media_id', 'mediaId')->refrences('media', ['mediaId'])
            ->column('urlId')->bigInt()->unsigned()->notNull()
            ->foreignKey('urlId_key', 'urlId')->refrences('url', ['urlId'])
            ->innoDb()
            ->executeSqlQuery();
        Seeder::post();
    }

    public function customer()
    {
        $this->createTable('customer')
            ->column('customerId')->id()
            ->column('mobileNumber')->bigInt()->unsigned()->notNull()
            ->column('fullName')->varchar(255)->nullable()
            ->column('address')->varchar(1500)->nullable()
            ->column('roleId')->bigInt()->unsigned()->default(2)
            ->foreignKey('customer_roleId_key', 'roleId')->refrences('user_role', ['roleId'])
            ->innoDb()
            ->executeSqlQuery();
    }

    public function htmlElements()
    {
        $this->createTable('htmlTags')
            ->column('elementId')->id()
            ->column('elementTitle')->varchar(250)
            ->column('tags')->varchar(1000)
            ->innoDb()
            ->executeSqlQuery();
        Seeder::htmlElements();
    }
}
