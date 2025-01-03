<?php
include HEADER;
?>
<h1>asnwer ticket</h1>
<h2><?= $ticket->ticketTitle; ?></h2>
<p><?= $ticket->ticketText; ?></p>
<p>متن پاسخ: <?= $ticket->answer ?? ''; ?></p>
<form action="api/ticket/answerTicket" method="post" id="registerTicketForm ">
    <label for="answer">متن پاسخ</label>
    <textarea name="answer" id="answer" rows="5" cols="50"></textarea>
    <input type="hidden" name="ticketId" value="<?= $ticketId; ?>">
    <input type="submit" value="ثبت">
</form>
<span id="answerTicketErrorMessage"></span>
<?php
if (strpos($_GET['url'], '/ticket/answer')):
?>
    <script src="assets/js/answer.js"></script>
<?php
endif;
include FOOTER;
