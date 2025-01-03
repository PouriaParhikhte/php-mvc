<?php

use Core\Helpers\Token\GetDecodedToken;
use Core\Helpers\Token\GetMessage;
use Core\Helpers\Token\GetPreviousToken;

include HEADER;

if (isset($content))
    foreach ($content as $index => $post) {
?>
    <h2><?= $post->title; ?></h2>
    <p><?= $post->content; ?></p>
<?php
    }
echo $pagination ?? null;
?>
<form action="api/user/signup" method="post">
    <label for="username">نام کاربری</label>
    <input type="text" name="username" id="username">
    <label for="password">رمز عبور</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="ثبت نام">
</form>
<span id="signupErrorMessage"><?= GetMessage::getInstance()->showMessage('signupErrorMessage'); ?></span>
<br><br>
<?php
$token = GetDecodedToken::getInstance()->fetch();
if (!isset($token->userId) || !isset($token->roleId) && $token->roleId !== 2) {
?>
    <form action="api/user/login" method="post">
        <input type="text" name="username" placeholder="نام کاربری">
        <input type="password" name="password" placeholder="رمز عبور">
        <input type="submit" value="ورود">
    </form>
    <span id="loginErrorMessage"><?= GetMessage::getInstance()->showMessage('loginErrorMessage'); ?></span>
<?php
}
include FOOTER;
