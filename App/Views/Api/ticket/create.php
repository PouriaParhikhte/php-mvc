<?php

use Core\Helpers\Token\GetMessage;

include HEADER;
?>
<h1>create ticket</h1>
<form action="api/ticket/register" method="post">
    <label for="ticketTitle">عنوان تیکت</label>
    <input type="text" name="ticketTitle" id="ticketTitle">
    <label for="ticketText">متن تیکت</label>
    <textarea name="ticketText" id="ticketText" rows="5" cols="50"></textarea>
    <input type="submit" value="ثبت">
</form>
<span id="message"><?= GetMessage::getInstance()->showMessage('message'); ?></span>
<script src="assets/js/ticket.js"></script>
<script src="assets/js/logout.js"></script>
<?php
include FOOTER;
