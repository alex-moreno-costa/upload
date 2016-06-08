<?php

namespace Upload\Storage;

use Upload\Storage\StorageInterface;
use Upload\Storage\StorageException;

/**
 * Description of Storage
 *
 * @author Alex Moreno
 */
class Storage implements StorageInterface
{
    private $directory;
    private $create = false;
    
    public function __construct($directory, $create = false)
    {
        $this->create = (bool) $create;
        
        if (!is_dir($directory) && $this->create === false) {
            $msg = sprintf('O diretório "%s" não existe', $directory);
            throw new \InvalidArgumentException($msg);
        }
        
        if (!is_dir($directory) && $this->create === true) {
            $directory = $this->createDirectory($directory);
        }
        
        if (!is_writable($directory)) {
            $msg = sprintf('O dietório "%s" não tem permissão para escrita', $directory);
            throw new \InvalidArgumentException($msg);
        }
        
        $this->directory = realpath($directory);
    }
    
    public function getDirectory()
    {
        return $this->directory;
    }

    private function validateDirectory($directory)
    {
        if (!is_dir($directory)) {
            return false;
        }
        
        if (!is_writable($directory)) {
            return false;
        }
     
        return true;
    }
    
    private function createDirectory($directory)
    {
        if (!mkdir($directory, 0777, true)) {
            $msg = sprintf('Não foi possível criar o diretório "%s"', $directory);
            throw new \RuntimeException($msg);
        }
        
        if (!$this->validateDirectory($directory)) {
            $msg = sprintf('Diretório "%s" criado porem não foi validado', $directory);
            throw new \RuntimeException($msg);
        }
        
        return $directory;
    }
}