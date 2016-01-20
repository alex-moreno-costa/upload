<?php

namespace Alex\Upload;

class UploadConfig
{
    private $upload_tmp_dir;
    private $upload_max_filesize;
    private $file_uploads;
    private $max_file_uploads;

    public function __construct()
    {
        $this->upload_tmp_dir = ini_get('upload_tmp_dir');
        $this->upload_max_filesize = ini_get('upload_max_filesize');
        $this->file_uploads = ini_get('file_uploads');
        $this->max_file_uploads = ini_get('max_file_uploads');
    }

    public function getUploadTmpDir()
    {
        return $this->upload_tmp_dir;
    }

    public function getUploadMaxFileSize()
    {
        return $this->upload_max_filesize;
    }

    public function getFileUploads()
    {
        return $this->file_uploads;
    }

    public function getMaxFileUploads()
    {
        return $this->max_file_uploads;
    }
}