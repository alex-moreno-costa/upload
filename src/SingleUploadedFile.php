<?php

namespace Alex\Upload;

use Alex\Upload\UploadException;
use Alex\Upload\UploadedFile;
use Alex\Upload\Upload;

class SingleUploadedFile extends Upload
{
    /**
     * Constructs the class with the minimal requeriments to upload a single file sucessfuly
     * @param array $uploadedFile the uploaded file $_FILES['file']
     * @param string $targetDirectory if the given dictory do not exists, the class tries to create
     * @throws UploadException
     */
    public function __construct(array $uploadedFile, $targetDirectory)
    {
        $objUploadedFile = new UploadedFile($uploadedFile);
        $this->setUploadedFile($objUploadedFile);
        $this->setTargetDirectory($targetDirectory);
        $this->setFileNewName($objUploadedFile->getFileName());
    }
    
}