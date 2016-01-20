<?php

namespace Alex\File;

/**
 * Description of Arquivo
 *
 * @author alex
 */
class File extends \SplFileInfo
{
    protected $mimeType;
    
    public function __construct($filename)
    {
        parent::__construct($filename);
        
        if (!$this->isFile()) {
            throw new \RunArgumentException(sprintf('The given path %s is not a valid file', $filename));
        }
        
        $this->dismemberFile($filename);
    }
    
    private function dismemberFile($filename)
    {
        $this->mimeType = mime_content_type($filename);
    }
    
    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getFilename()
    {
        return pathinfo($this->getRealPath(), PATHINFO_FILENAME);
    }
    
    /**
     * Rename the file and re-builds the class with the new file
     * @param string $newName
     * @param string $newExtension
     * @return boolean
     */
    public function rename($newName, $extension = null)
    {
        $newName = filter_var($newName, FILTER_SANITIZE_STRING);
        $newExtension = (null === $extension) ? $this->getExtension() : filter_var(substr_replace('.', null, $extension), FILTER_SANITIZE_STRING);
        
        $newFile = realpath($this->getPath()) . '/' . $newName . '.' . $newExtension;
        $result = rename($this->getRealPath(), $newFile);
        
        $this->__construct($newFile);
        return $result;
    }
    
    /**
     * Delete the file
     * @return boolean
     */
    public function delete()
    {
        return unlink($this->getRealPath());
    }
    
    /**
     * Moves the file to a given directory
     * @param string $newDirectory
     * @param boolean $overwrite
     * @return boolean
     * @throws \InvalidArgumentException
     * @throws \RunArgumentException
     */
    public function move($newDirectory, $overwrite = true)
    {
        if (!is_dir($newDirectory)) {
            throw new \InvalidArgumentException(sprintf('The given directory "%s" is invalid', $newDirectory));
        }
        
        $oldname = $this->getRealPath();
        $newname = realpath($newDirectory) . '/' . $this->getFilename();
        
        if (is_file($newname) && $overwrite === false) {
            throw new \RunArgumentException(sprintf('Cannot overwrite file %s', $newname));
        }
        
        $result = rename($oldname, $newname);
        $this->__construct($newname);
        
        return $result;
    }
    
    /**
     * Copies the file to a given directory
     * @param string $newDirectory
     * @param boolean $overwrite
     * @return boolean
     * @throws \InvalidArgumentException
     * @throws \RunArgumentException
     */
    public function copy($newDirectory, $overwrite = true)
    {
        if (!is_dir($newDirectory)) {
            throw new \InvalidArgumentException(sprintf('The given directory "%s" is invalid', $newDirectory));
        }
        
        $oldname = $this->getRealPath();
        $newname = realpath($newDirectory) . '/' . $this->getFilename();
        
        if (is_file($newname) && $overwrite === false) {
            throw new \RunArgumentException(sprintf('Cannot overwrite file %s', $newname));
        }
        
        $result = copy($oldname, $newname);
        $this->__construct($newname);
        
        return $result;
    }
}