<?php

namespace Alex\Upload;

use Alex\Upload\UploadException;

class Upload
{
    /**
     * The file name of the uploaded file
     * @var string
     */
    private $uploadedName;

    /**
     * The mime type of the uploated file
     * @var string
     */
    private $uploadedMimeType;

    /**
     * The temporary directory of the uploaded file
     * @var string
     */
    private $uploadedTempFile;

    /**
     * The number error of the uploaded file
     * @var int
     */
    private $uploadedError;

    private $uploadedSize;
    
    /**
     * The target directory to moves the uploaded file
     * @var string
     */
    private $targetDirectory;

    /**
     * 
     * @var string
     */
    private $fileName;

    /**
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @var array
     */
    private $allowedMimeTypes;

    /**
     * 
     * @param array $uploadedFile
     * @param string $targetDirectory
     * @param array $allowedMimeTypes
     */
    public function __construct(array $uploadedFile, $targetDirectory = null, array $allowedMimeTypes = array(), $fileNewName = null)
    {
        if (!is_uploaded_file($uploadedFile['tmp_name'])) {
            throw new \RuntimeException('O arquivo informado não é de um upload');
        }
        
        if ($uploadedFile['error'] != 0) {
            throw new UploadException($uploadedFile['error']);
        }
        
        $this->fileName = $uploadedFile['name'];
        $this->uploadedError = $uploadedFile['error'];
        $this->uploadedMimeType = $uploadedFile['type'];
        $this->uploadedTempFile = $uploadedFile['tmp_name'];
        $this->uploadedSize = $uploadedFile['size'];
        
        $this->setTargetDirectory($targetDirectory);
        $this->setAllowedMimeTypes($allowedMimeTypes);
        $this->setFileNewName($fileNewName);
    }

    /**
     * TODO Auto-generated comment.
     */
    private function validate()
    {
        if (count($this->allowedMimeTypes) > 0 && !in_array($this->uploadedMimeType, $this->allowedMimeTypes)) {
            throw new \OutOfRangeException(sprintf('O tipo do arquivo "%s" não é permitido pelo sistema', $this->uploadedMimeType));
        }
    }

    /**
     * TODO Auto-generated comment.
     */
    public function setAllowedMimeTypes($mimeTypes)
    {
        $this->allowedMimeTypes = $mimeTypes;
    }

    /**
     * TODO Auto-generated comment.
     */
    public function setFileNewName($newName)
    {
        if (null === $newName || empty($newName)) {
            $this->fileName = $this->uploadedName;
        }
        
        $this->fileName = filter_var($newName, FILTER_SANITIZE_STRING);
    }

    /**
     * TODO Auto-generated comment.
     */
    public function setTargetDirectory($directory, $create = true)
    {
        if (null === $directory) {
            return;
        }
        
        if (!is_dir($directory) && $create === false) {
            throw new \RuntimeException(sprintf('O diretório informado "%s" não existe', $directory));
        }
        
        if (!is_dir($directory) && $create === true) {
            mkdir($directory, 0777, true);
        }
        
        $this->targetDirectory = realpath($directory);
    }

    /**
     * TODO Auto-generated comment.
     */
    public function getFile()
    {
        if (!$this->file instanceof SplFileInfo) {
            throw new \InvalidArgumentException('Faça o upload do arquivo antes de executar esse metodo');
        }
        
        return $this->file;
    }

    /**
     * TODO Auto-generated comment.
     */
    public function run()
    {
        $this->validate();
        $destination = $this->targetDirectory . '/' . $this->fileName;
        move_uploaded_file($this->uploadedTempFile, $destination);
        $this->file = new \SplFileInfo($destination);
        return $this->file;
    }
}