<?php

namespace Alex\Upload;

/**
 * @author alex
 */
interface IUploadedFile
{
    public function getFileName();
    public function getFileSize();
    public function getFileMimeType();
    public function getTemporaryFile();
    public function getFileError();
}