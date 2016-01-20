<?php
require './vendor/autoload.php';

use Alex\Upload\Upload;
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="arquivo" />
    <input type="submit" />
</form>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload = new Upload($_FILES['arquivo']);
    $upload->setTargetDirectory(__DIR__ . '/novapasta/sub1/sub2/sub3');
    $upload->setFileNewName('abc.jpg');
    $upload->setAllowedMimeTypes(array('image/jpg', 'image/jpeg'));
    $file = $upload->run();
    var_dump($file);
}
?>