<?php
include HEADER;
echo $elements->tags ?? null;
echo $token->temporaryCode ?? null;
echo $token->message ?? null;
?>
<br>
<br>
<?= $posts; ?>

<script src="Assets/Js/Jquery-3.7.1.min.js"></script>
<script src="Assets/Js/index.js"></script>
<?php
include FOOTER;
