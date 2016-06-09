<?php

namespace Upload;

use Upload\FileInfoInterface;

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
        
        $this->setFilename($uploadedFile['name']);
        $this->setMimeType();
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
        return $this->extension;
    }
    
    public function getBasename()
    {
        return $this->filename . '.' . $this->extension;
    }

    public function setFilename($filename)
    {
        $this->filename = pathinfo($filename, PATHINFO_FILENAME);
    }

    private function setMimeType()
    {
        $this->mimeType = mime_content_type($this->getRealPath());
    }

    private function setMd5()
    {
        $this->md5 = md5_file($this->getRealPath());
    }
    
    private function setExtension()
    {
        $extension = pathinfo($this->getRealPath(), PATHINFO_EXTENSION);
        $this->extension = strtolower($extension);
    }
}