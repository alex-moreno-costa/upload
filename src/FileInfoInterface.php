<?php

namespace Upload;

/**
 *
 * @author Daniel
 */
interface FileInfoInterface
{
    public function getMd5();
    public function getMimeType();
    public function getSize();
    public function getExtension();
    public function getFilename();
    public function getBaseName();
    
    public function setFilename($filename);
}
