<?php
include HEADER;
?>
<h1>tickets</h1>
<div id="tickets">
    <?php
    if (isset($tickets)) {
        foreach ($tickets as $ticket) {
    ?>
            <h2><a href="api/ticket/view/<?= $ticket->ticketId; ?>"><?= $ticket->ticketTitle; ?></a></h2>
            <p><?= $ticket->ticketText; ?></p>
            <p>پاسخ: <?= $ticket->answer; ?></p>
    <?php
        }
        echo $pagination;
    } else
        echo 'چیزی برای نمایش وجود ندارد';
    ?>
</div>
<script src="assets/js/logout.js"></script>
<?php
include FOOTER;
