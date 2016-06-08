<?php

namespace Alex\Upload;

use Alex\Upload\UploadedFile;
use Alex\Upload\Upload;

class MultipleUploadedFile
{

    private $uploadedFiles = array();
    private $files = array();
    private $allowOverwrite = false;
    private $allowedMimeType = array();
    private $targetDirectory;
    private $createDirectory = true;

    /**
     * Constructs the class with the minimal requeriments to upload multiple files sucessfuly
     * @param array $uploadedFile the uploaded file $_FILES['file']
     * @param string $targetDirectory if the given dictory do not exists, the class tries to create
     * @throws UploadException
     */
    public function __construct(array $uploadedFile, $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->desmemberFiles($uploadedFile);
    }

    /**
     * Desmember uploaded files and create instances of UploadedFile
     * @param array $uploadedFile
     */
    private function desmemberFiles(array $uploadedFile)
    {
        $limit = count($uploadedFile['error']);
        for ($i = 0; $i < $limit; $i++) {
            $file = array(
                'tmp_name' => $uploadedFile['tmp_name'][$i],
                'name' => $uploadedFile['name'][$i],
                'size' => $uploadedFile['size'][$i],
                'error' => $uploadedFile['error'][$i],
                'type' => $uploadedFile['type'][$i],
            );
            $this->uploadedFiles[] = new UploadedFile($file);
        }
    }
    
    /**
     * Define the directory for the upload file be moved, case the directory does 
     * not exists and the parameter create is true, the dicrectory will be created
     * @param string $directory
     * @param boolean $create
     */
    public function setTargetDirectory($directory, $create = true)
    {
        $this->targetDirectory = $directory;
        $this->createDirectory = boolval($create);
    }
    
    public function getFiles()
    {
        if (count($this->files) == 0) {
            throw new \BadMethodCallException('To use this method please execute the method run() first');
        }

        return $this->files;
    }
    
    /**
     * Define whether an existing file will be overwritten or not
     * @param boolean $allowOverwrite TRUE or FALSE
     */
    public function setAllowOverwrite($allowOverwrite)
    {
        $this->allowOverwrite = $allowOverwrite;
    }
    
    public function setAllowedMimeTypes(array $mimeTypes)
    {
        $this->allowedMimeType = $mimeTypes;
    }

    public function run()
    {
        foreach ($this->uploadedFiles as $uploadedFile) {
            $upload = new Upload($uploadedFile);
            $upload->setAllowOverwrite($this->allowOverwrite);
            $upload->setAllowedMimeTypes($this->allowedMimeType);
            $upload->setFileNewName($uploadedFile->getFileName());
            $upload->setTargetDirectory($this->targetDirectory, $this->createDirectory);
            $this->files[] = $upload->run();
            unset($upload);
        }
        
        return $this->files;
    }
}