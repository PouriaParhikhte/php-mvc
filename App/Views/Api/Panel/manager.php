<?php

use Core\Helpers\Token\fetchValueFromToken;

include HEADER;
?>
<h1>manager</h1>
<form action="api/panel/login" method="post" id="panelLoginForm">
    <input type="text" name="username" placeholder="نام کاربری">
    <input type="password" name="password" placeholder="رمز عبور">
    <input type="submit" id="loginButton" value="ورود">
</form>
<div id="panelErrorMessage"><?= fetchValueFromToken::getInstance()->fetch('message'); ?></div>
<script src="assets/js/panel.js"></script>
<?php
include FOOTER;
