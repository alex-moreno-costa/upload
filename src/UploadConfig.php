<?php

namespace Alex\Upload;

/**
 * This class returns the main settings of your server to upload a file.
 * These settings can not be changed via script only for php.ini or .htaccess file
 */
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

    /**
     * See the server configuration for temporary directory
     * @return string
     */
    public function getUploadTmpDir()
    {
        return $this->upload_tmp_dir;
    }

    /**
     * See the maximum file size for a uploaded file
     * @return string
     */
    public function getUploadMaxFileSize()
    {
        return $this->upload_max_filesize;
    }

    /**
     * See if the server allow or not a upload
     * @return boolean
     */
    public function getFileUploads()
    {
        return $this->file_uploads;
    }

    /**
     * See the maximum uploads simultaneously
     * @return int
     */
    public function getMaxFileUploads()
    {
        return $this->max_file_uploads;
    }
    
    public function __toString()
    {
        $msg = '<p>See bellow the configuration for upload in your server:</p>';
        $msg.= "The maximum number of files allowed to be uploaded simultaneously is {$this->max_file_uploads}<br />";
        $msg.= "The maximum file size permited for a upload is {$this->upload_max_filesize}bytes<br />";
        $msg.= "The temporary directory for upload files is \"{$this->upload_tmp_dir}\"<br />";
        $msg.= "Your server permit uploads {$this->file_uploads}<br />";
        
        return $msg;
    }
}