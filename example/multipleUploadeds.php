<?php

require __DIR__ . '/../vendor/autoload.php';
header("Content-type='text/html', charset='utf8'");

use Alex\Upload\MultipleUploadedFile;

try {
    $upload = new MultipleUploadedFile($_FILES['file'], __DIR__ . '/uploads');
    $upload->setAllowOverwrite(false);
    $upload->setAllowedMimeTypes(array('image/png', 'image/jpg', 'image/gif', 'image/jpeg'));
    $files = $upload->run();
} catch (Exception $ex) {
    $saida = 'Não foi possível fazer o upload dos arquivos, segue abaixo a descrição'
            . 'do erro:<br />%s';
    die(sprintf($saida, $ex->getMessage()));
}

echo 'Segue abaixo o caminho dos arquivo: <br/>';

foreach($files as $file) {
    echo $file->getRealPath() . '<br />';
}