<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\Upload\SingleUploadedFile;

try {
    $upload = new SingleUploadedFile($_FILES['file'], __DIR__ . '/uploads');
    $upload->setAllowOverwrite(false);
    $upload->setAllowedMimeTypes(array('image/png', 'image/jpg', 'image/gif', 'image/jpeg'));
    $upload->setFileNewName(strtotime('now'));
    $file = $upload->run();
} catch (Exception $ex) {
    echo $ex->getMessage();
    die();
}

echo 'Segue o caminho para o novo arquivo: ' . $file->getRealPath();