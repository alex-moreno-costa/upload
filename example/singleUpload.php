<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\Upload\SingleUploadedFile;

$upload = new SingleUploadedFile($_FILES['file'], __DIR__ . '/uploads');
$upload->setAllowOverwrite(true);
$file = $upload->run();

echo 'Segue o caminho para o novo arquivo: ' . $file->getRealPath();