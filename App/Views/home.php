<?php
include HEADER;
echo $elements->tags ?? null;
echo $token->temporaryCode ?? null;
echo $token->message->mobileNumber ?? $token->message->temporaryCode ?? null;
?>
<br>
<form action="upload" method="post" enctype="multipart/form-data">
    <input type="file" name="my_file" accept="image/jpg, image/jpeg, image/png">
    <input type="submit" value="upload">
</form>
<?= $token->message->upload ?? null; ?>
<br>
<?= $posts; ?>

<script src="Assets/Js/Jquery-3.7.1.min.js"></script>
<script src="Assets/Js/index.js"></script>
<?php
include FOOTER;
