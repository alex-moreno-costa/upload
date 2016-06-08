<?php

namespace Upload;

use Upload\FileInfoInterface;
use Upload\UploadException;

/**
 * Description of UploadedFile
 *
 * @author alex
 */
class UploadedFile extends \SplFileInfo implements FileInfoInterface
{
    private $filename;
    private $mimeType;
    private $md5;
    private $extension;
    
    public function __construct(array $uploadedFile)
    {
        parent::__construct($uploadedFile['tmp_name']);
        
        $this->setMimeType($uploadedFile['type']);
        $this->setFilename($uploadedFile['name']);
        $this->setMd5();
        $this->setExtension();
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getMd5()
    {
        return $this->md5;
    }
    
    public function getExtension()
    {
        parent::getExtension();
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    private function setMd5()
    {
        $this->md5 = md5_file($this->getRealPath());
    }
    
    private function setExtension()
    {
        $this->extension = '';
    }
}