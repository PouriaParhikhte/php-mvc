<?php

use Core\Helpers\Helper;

include HEADER;
?>
<h1>received tickets</h1>

<div id="tickets">
    <?php
    if (is_array($tickets))
        foreach ($tickets as $ticket) {
    ?>
        <h2><?= $ticket->ticketTitle; ?></h2>
        <p><?= $ticket->ticketText; ?></p>
        <p>
            پاسخ: <?= $ticket->answer ?? ''; ?>
        </p>
        <p>
            <a href="api/ticket/answer/<?= $ticket->ticketId; ?>">ثبت پاسخ</a>
        </p>
    <?php
        }
    echo $pagination;
    ?>
</div>
<?php
include FOOTER;
