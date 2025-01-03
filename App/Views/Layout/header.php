<?php

use Core\Helpers\Configs;
?>
<!doctype html>
<html lang="en">

<head>
    <base href="<?= Configs::baseUrl(); ?>">
    <meta CHARSET="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= Configs::domain(); ?></title>
</head>

<body dir="rtl">
    <nav><?= $menu ?? ''; ?></nav>