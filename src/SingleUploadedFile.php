<?php

namespace Alex\Upload;

use Alex\Upload\UploadedFile;
use Alex\Upload\Upload;

class SingleUploadedFile
{
    /**
     * Instance of Upload
     * @var Upload
     */
    private $upload;
    
    /**
     * Instance of UploadedFile
     * @var UploadedFile
     */
    private $uploadedFile;
    
    /**
     * Constructs the class with the minimal requeriments to upload a single file sucessfuly
     * @param array $uploadedFile the uploaded file $_FILES['file']
     * @param string $targetDirectory if the given dictory do not exists, the class tries to create
     */
    public function __construct(array $uploadedFile, $targetDirectory)
    {
        $this->uploadedFile = new UploadedFile($uploadedFile);
        $this->upload = new Upload($this->uploadedFile);
        $this->upload->setTargetDirectory($targetDirectory);
        $this->upload->setFileNewName($this->uploadedFile->getFileName());
    }
    
    public function setFileNewName($newName)
    {
        $this->upload->setFileNewName($newName);
    }
    
    public function setTargetDirectory($directory, $create = true)
    {
        $this->upload->setTargetDirectory($directory, $create);
    }
    
    public function getFile()
    {
        return $this->upload->getFile();
    }
    
    public function run()
    {
        return $this->upload->run();
    }
    
    public function setAllowedMimeTypes(array $mimeTypes)
    {
        $this->upload->setAllowedMimeTypes($mimeTypes);
    }
    
    public function setAllowOverwrite($overwrite)
    {
        $this->upload->setAllowOverwrite($overwrite);
    }
    
    public function validate()
    {
        return $this->upload->validate();
    }
}