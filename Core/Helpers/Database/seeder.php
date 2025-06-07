<?php

namespace Core\Helpers\Database;

use Core\Crud\Insert;
use Core\Crud\Select;

class Seeder
{
    public static function url(): void
    {
        $urls = [
            ['url' => 'home', 'persianUrl' => 'صفحه اصلی', 'sort' => 1],
            ['url' => 'products', 'persianUrl' => 'محصولات', 'dropDown' => '1', 'sort' => 2],
            ['url' => 'product1', 'persianUrl' => 'محصول1', 'parentId' => 2, 'sort' => 1]
        ];
        self::seeder($urls);
    }

    public static function user_role(): void
    {
        $roles = [
            ['roleName' => 'admin'],
            ['roleName' => 'customer'],
            ['roleName' => 'visitor']
        ];
        self::seeder($roles);
    }

    public static function user()
    {
        $password = password_hash('12345', PASSWORD_BCRYPT);
        $users = [
            ['username' => 'admin', 'password' => $password, 'accessToken' => 'abc', 'roleId' => 1]
        ];
        self::seeder($users);
    }

    public static function post(): void
    {
        $content = [
            ['postTitle' => 'welcome', 'postText' => 'some text some text some text', 'urlId' => '1'],
            ['postTitle' => 'post1', 'postText' => 'post1', 'urlId' => '1'],
            ['postTitle' => 'post2', 'postText' => 'post2', 'urlId' => '1'],
            ['postTitle' => 'post3', 'postText' => 'post3', 'urlId' => '1'],
            ['postTitle' => 'post4', 'postText' => 'post4', 'urlId' => '1']
        ];
        self::seeder($content);
    }

    public static function customer_panel_url(): void
    {
        $userPanelUrls = [
            ['url' => 'user/dashboard', 'persianUrl' => 'داشبورد', 'sort' => 1],
            ['url' => 'ticket', 'persianUrl' => 'تیکت پشنیبانی', 'dropDown' => '1', 'sort' => 2],
            ['url' => 'ticket/create', 'persianUrl' => 'ایجاد تیکت', 'parentId' => 2, 'sort' => 1],
            ['url' => 'ticket/index', 'persianUrl' => 'تیکت های ارسال شده', 'parentId' => 2, 'sort' => 2],
            ['url' => 'user/logout', 'persianUrl' => 'خروج از حساب کلربری', 'sort' => 3]
        ];
        self::seeder($userPanelUrls);
    }

    public static function admin_panel_url(): void
    {
        $adminPanelUrls = [
            ['url' => 'panel/dashboard', 'englishUrl' => 'dashboard', 'persianUrl' => 'داشبورد', 'sort' => 1],
            ['url' => 'pages', 'englishUrl' => 'pages', 'persianUrl' => 'صفحات', 'dropdown' => '1', 'sort' => 2],
            ['url' => 'page/create', 'englishUrl' => 'create page', 'persianUrl' => 'ایجاد صفحه جدید', 'parentId' => 2, 'sort' => 1],
            ['url' => 'page/index', 'englishUrl' => 'pages list', 'persianUrl' => 'لیست صفحات', 'parentId' => 2, 'sort' => 2],
            ['url' => 'posts', 'englishUrl' => 'posts', 'persianUrl' => 'نوشته ها', 'dropdown' => '1', 'sort' => 3],
            ['url' => 'post/create', 'englishUrl' => 'create page', 'persianUrl' => 'ایجاد نوشته جدید', 'parentId' => 5, 'sort' => 1],
            ['url' => 'post/index', 'englishUrl' => 'posts list', 'persianUrl' => 'لیست نوشته ها', 'parentId' => 5, 'sort' => 2],
            ['url' => 'panel/logout', 'englishUrl' => 'logout', 'persianUrl' => 'خروج از حساب کلربری', 'sort' => 3]
        ];
        self::seeder($adminPanelUrls);
    }

    public static function htmlElements()
    {
        $elements = [
            ['elementTitle' => 'customerAccountForm', 'tags' => '<form action="customer/account" method="post" id="mobileNumber"><label for="mobile_number">شماره موبایل</label><input type="number" name="mobileNumber" id="mobile_number"><input type="submit" value="ارسال"></form>'],
            ['elementTitle' => 'temporaryCodeForm', 'tags' => '<form action="customer/temporaryCode" method="post" id="temporaryCode"><label for="temporary_code">گد یکبار مصرف</label><input type="number" name="temporaryCode" id="temporary_code"><input type="submit" value="ارسال"></form>'],
        ];
        self::seeder($elements);
    }

    private static function seeder(array $valuesToInsert)
    {
        $existsRecords = Select::getInstance()->__invoke($GLOBALS['table'])->select()->fetchResult();
        if ($diff = array_diff_key($valuesToInsert, $existsRecords))
            array_walk($diff, [(new self), 'insertIntotables']);
    }

    private function insertIntotables($input)
    {
        Insert::getInstance()->__invoke($GLOBALS['table'])->insert($input)->result();
    }
}
