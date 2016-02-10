<?php

namespace Alex\Upload;

use Alex\Upload\UploadException;
use Alex\Upload\UploadedFile;
use Alex\Upload\Upload;

class MultipleUploadedFile extends Upload
{

    private $uploadedFiles = array();
    private $files = array();

    /**
     * Constructs the class with the minimal requeriments to upload a single file sucessfuly
     * @param array $uploadedFile the uploaded file $_FILES['file']
     * @param string $targetDirectory if the given dictory do not exists, the class tries to create
     * @throws UploadException
     */
    public function __construct(array $uploadedFile, $targetDirectory)
    {
        $this->setTargetDirectory($targetDirectory);
        $this->desmemberFiles($uploadedFile);
    }

    private function desmemberFiles(array $uploadedFile)
    {
        $limit = count($uploadedFile['error']);
        for ($i = 0; $i < $limit; $i++) {
            $this->uploadedFiles[] = array(
                'tmp_name' => $uploadedFile['tmp_name'][$i],
                'name' => $uploadedFile['name'][$i],
                'size' => $uploadedFile['size'][$i],
                'error' => $uploadedFile['error'][$i],
                'type' => $uploadedFile['type'][$i],
            );
        }
    }

    public function run()
    {
        foreach ($this->uploadedFiles as $uploadedFile) {
            $objUploadedFile = new UploadedFile($uploadedFile);
            $this->setUploadedFile($objUploadedFile);
            $this->setFileNewName($objUploadedFile->getFileName());
            $this->files[] = parent::run();
        }
        
        return $this->files;
    }
}