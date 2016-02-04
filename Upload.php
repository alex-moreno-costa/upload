<?php

namespace Alex\Upload;

use Alex\Upload\UploadException;
use Alex\Upload\UploadConfig;
use SplFileInfo;
use Alex\Upload\IUploadedFile;

class Upload
{
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
     * @var UploadConfig 
     */
    private $config;
    
    /**
     *
     * @var IUploadedFile
     */
    private $uploadedFile;

    /**
     * Constructs the class with the minimal requeriments to upload a file sucessfuly
     * @param array $uploadedFile the uploaded file $_FILES['file']
     * @param string $targetDirectory if the given dictory do not exists, the class tries to create
     * @throws UploadException
     */
    public function __construct(array $uploadedFile, $targetDirectory)
    {
        $this->uploadedFile = new UploadedFile($uploadedFile);
        $this->setTargetDirectory($targetDirectory);
        $this->setFileName($this->uploadedFile->getFileName());
    }

    /**
     * TODO Auto-generated comment.
     */
    private function validate()
    {
        if (null !== $this->allowedMimeTypes && !in_array($this->uploadedMimeType, $this->allowedMimeTypes)) {
            throw new \OutOfBoundsException(
                    sprintf('O tipo do arquivo "%s" não é permitido pelo sistema', $this->uploadedMimeType)
                    );
        }
    }

    /**
     * Inform the mime type to compare with the uploaded file.
     * @param array $mimeTypes The mime types to check
     */
    public function setAllowedMimeTypes(array $mimeTypes)
    {
        $this->allowedMimeTypes = $mimeTypes;
    }

    /**
     * Set a new name to save the uploaded file
     * @param string $newName Gives a file name with extension (file.txt)
     */
    public function setFileName($newName)
    {
        if (!preg_match('/^(.*)\.([a-zA-Z])+$/', $newName)) {
            throw new \InvalidArgumentException(sprintf('The given name "%s" is not valid, try something like "file.txt"', $newName));
        }
        
        $this->fileName = filter_var($newName, FILTER_SANITIZE_STRING);
    }

    /**
     * Define the directory for the upload file be moved, case the directory does 
     * exists and the parameter create is true, the dicrectory is created
     * @param string $directory
     * @param boolean $create
     * @throws \RuntimeException if the given directory does exists and the create parameter is false
     */
    public function setTargetDirectory($directory, $create = true)
    {
        if (!is_dir($directory) && $create === false) {
            throw new \RuntimeException(sprintf('O diretório informado "%s" não existe', $directory));
        }
        
        if (!is_dir($directory) && $create === true) {
            mkdir($directory, 0777, true);
        }
        
        $this->targetDirectory = realpath($directory);
    }

    /**
     * Returns an instance of SplFileInfo, but first the run method should be executed
     * @return SplFileInfo
     * @throws \BadMethodCallException Throws an exception if the run method does not run
     */
    public function getFile()
    {
        if (!$this->file instanceof SplFileInfo) {
            throw new \BadMethodCallException('Faça o upload do arquivo antes de executar esse metodo');
        }
        
        return $this->file;
    }

    /**
     * Validate and processes the uploaded file, if is sucessfuly return a 
     * SplFileInfo Intance of the new file
     * @return SplFileInfo return SplFileInfo if the upload process is successed
     */
    public function run()
    {
        $this->validate();
        $destination = $this->targetDirectory . '/' . $this->fileName;
        move_uploaded_file($this->uploadedTempFile, $destination);
        $this->file = new SplFileInfo($destination);
        return $this->file;
    }
}