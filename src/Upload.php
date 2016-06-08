<?php

namespace Alex\Upload;

use Alex\Upload\UploadedFile;
use \SplFileInfo;

class Upload
{

    /**
     * The target directory to moves the uploaded file
     * @var string
     */
    private $targetDirectory;

    /**
     * A array of permited mime type for the upload
     * @var array
     */
    private $allowedMimeTypes = array();

    /**
     * Permite ou não sobrescrever o arquivo caso ele exista.
     * @var boolean O valor padrão é falso
     */
    private $allowOverwrite = false;

    /**
     * Recebe a instancia do objeto UploadedFile
     * @var UploadedFile
     */
    private $uploadedFile;

    /**
     * Nome que sera usado para salvar o arquivo
     * @var string
     */
    private $fileName;

    /**
     * Instancia do objeto SplFileInfo
     * @var SplFileInfo
     */
    private $file;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }
    
    /**
     * Define the directory for the upload file be moved, case the directory does 
     * not exists and the parameter create is true, the dicrectory will be created
     * @param string $directory
     * @param boolean $create
     * @throws \RuntimeException if the given directory does exists and the create parameter is false
     */
    public function setTargetDirectory($directory, $create = true)
    {
        $create = boolval($create);
        
        if (!is_dir($directory) && $create === false) {
            throw new \RuntimeException(sprintf('The Informed directory "%s" does not exist', $directory));
        }

        if (!is_dir($directory) && $create === true) {
            mkdir($directory, 0777, true);
        }

        $this->targetDirectory = realpath($directory);
    }

    /**
     * Define whether an existing file will be overwritten or not
     * @param boolean $allowOverwrite TRUE or FALSE
     */
    public function setAllowOverwrite($allowOverwrite)
    {
        $this->allowOverwrite = boolval($allowOverwrite);
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
     * @param string $newName Gives a file name with or without extension
     */
    public function setFileNewName($newName)
    {
        if (empty($newName) || !isset($newName)) {
            throw new \InvalidArgumentException('The given name is blank or null, please give a valid name');
        }
        
        if (!preg_match('/^(.*)\.([a-zA-Z])+$/', $newName)) {
            $this->fileName = filter_var($newName, FILTER_SANITIZE_STRING) . '.' . $this->uploadedFile->getExtension();
        } else {
            $this->fileName = filter_var($newName, FILTER_SANITIZE_STRING);
        }
    }

    /**
     * Returns an instance of SplFileInfo, but first the run method should be executed
     * @return SplFileInfo
     * @throws \BadMethodCallException Throws an exception if the run method does not run
     */
    public function getFile()
    {
        if (!$this->file instanceof SplFileInfo) {
            throw new \BadMethodCallException('To use this method please execute the method run() first');
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
        
        if (is_file($destination) && $this->allowOverwrite === false) {
            throw new \RuntimeException(
                    sprintf('The file "%s" already exist and the option overwrite is seted "%d"', 
                            $destination, $this->allowOverwrite
                            )
                    );
        }
        
        move_uploaded_file($this->uploadedFile->getTemporaryFile(), $destination);
        $this->file = new SplFileInfo($destination);
        return $this->file;
    }

    public function validate()
    {
        if (count($this->allowedMimeTypes) == 0) {
            return true;
        }
        
        if (!in_array($this->uploadedFile->getFileMimeType(), $this->allowedMimeTypes)) {
            throw new \OutOfBoundsException(
            sprintf('O tipo do arquivo "%s" não é permitido pelo sistema', $this->uploadedFile->getFileMimeType())
            );
        }

        return true;
    }
}