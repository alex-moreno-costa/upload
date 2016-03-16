<?php

namespace Alex\Upload;

use Alex\Upload\IUploadedFile;
use Alex\Upload\UploadException;

/**
 * Description of UploadedFile
 *
 * @author alex
 */
class UploadedFile implements IUploadedFile
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

    /**
     * The size of uploaded file
     * @var int
     */
    private $uploadedSize;
    
    public function __construct(array $uploadedFile)
    {
        if (!is_uploaded_file($uploadedFile['tmp_name'])) {
            throw new \RuntimeException('The informed file is not a upload');
        }
        
        if ($uploadedFile['error'] != 0) {
            throw new UploadException($uploadedFile['error']);
        }
        
        $this->uploadedError = (int) $uploadedFile['error'];
        $this->uploadedMimeType = $uploadedFile['type'];
        $this->uploadedTempFile = $uploadedFile['tmp_name'];
        $this->uploadedSize = (int) $uploadedFile['size'];
        $this->uploadedName = $uploadedFile['name'];
        
    }
    
    /**
     * Return the upload error
     * @return int
     */
    public function getFileError()
    {
        return $this->getFileError();
    }

    /**
     * Return the mime type of uploaded file
     * @return string
     */
    public function getFileMimeType()
    {
        return $this->uploadedMimeType;
    }

    /**
     * Return the file name of upload
     * @return string
     */
    public function getFileName()
    {
        return $this->uploadedName;
    }

    /**
     * Return the file size of upload
     * @return int
     */
    public function getFileSize()
    {
        return $this->uploadedSize;
    }

    /**
     * Return the temporary file path of upload
     * @return string
     */
    public function getTemporaryFile()
    {
        return $this->uploadedTempFile;
    }
    
    /**
     * Return the extension for upload
     * @return string
     */
    public function getExtension()
    {
        preg_match('/\.([a-zA-Z]{3,4})$/', $this->uploadedName, $matches);
        return $matches[1];
    }
}