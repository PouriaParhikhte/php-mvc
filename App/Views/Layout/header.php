<!doctype html>
<html lang="en">

<head>
    <base href="<?= SETTINGS->BASEURL; ?>">
    <meta CHARSET="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SETTINGS->DOMAIN; ?></title>
    <style>
        .dropdown,
        .active {
            pointer-events: none;
        }
    </style>
</head>

<body dir="rtl">
    <?= $menu ?? null; ?>