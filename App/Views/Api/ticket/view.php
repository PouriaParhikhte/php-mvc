<?php
include HEADER;
?>
<h1><?= $ticket->ticketTitle; ?></h1>
<p><?= $ticket->ticketText; ?></p>
<?php
if ($ticket->status === 1):
?>
    <p><?= $ticket->answer; ?></p>
<?php
endif;
include FOOTER;
