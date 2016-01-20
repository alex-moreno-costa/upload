<?php
/**
 * Simple class for files upload
 *
 * @author Alex Moreno
 * @version 3.0
 */
namespace Alex\Upload;

class Upload 
{
    /**
     * The file to be sent
     * @access protected
     * @var array $_FILES
     */
    protected $file;
     
    /**
     * The folder to receive the file
     * @access protected
     * @var String
     */
    protected $uploads_folder;
    
    /**
     * The allowed MIMEType for the file
     * @access protected
     * @var array
     */
    protected $allowed_type;
    
    /**
     * The max size of file (in MegaBytes)
     * @access protected
     * @var Double
     */
    protected $max_size = 2;
    
    /**
     * Overwrite file with same name?
     * @access protected
     * @var Boolean
     */
    protected $overwrite = true;
    
    /**
     * Constructor method. You can define the file, uploads folder, new file name and the language. Define too the default messages
     * @access public
     * @param $file File The file to be upload
     * @param $uploads_folder String The path of the receive folder
     * @param $file_name String The new name
     * @return Void
     */
    public function __construct($file = null, $uploads_folder = null, $file_name = null, $language = 'pt') 
    {
        $this->set_file($file);
        $this->set_file_name($file_name);
        
        if(isset($uploads_folder)) {
            $this->set_uploads_folder($uploads_folder);
        }
    }
    
    /**
     * Set a file
     * @access public
     * @param $file the file to be upload $_FILES
     * @return Void
     */
    public function set_file($file)
    {
        if(!isset($file)) {
            trigger_error('Parametro $_FILES não informado', E_USER_ERROR);
        }
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                throw new \Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Exception('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
                break;
            case UPLOAD_ERR_PARTIAL:
                throw new \Exception('The uploaded file was only partially uploaded');
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Exception('Nenhum arquivo foi submetido');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new \Exception('Missing a temporary folder');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                throw new \Exception('Failed to write file to disk');
                break;
            case UPLOAD_ERR_EXTENSION:
                throw new \Exception('A PHP extension stopped the file upload');
                break;
            case UPLOAD_ERR_OK:
                //sem erros
                break;
            
            default:
                break;
        }
        
        $this->file = $file;
        $this->set_ext();
    }
    
    /**
     * Set the extension of file
     * @access protected
     * @param $file File The file will be sent
     * @return Void
     */
    protected function set_ext()
    {
        $this->ext = pathinfo($this->file['name'], PATHINFO_EXTENSION);
    }
    
    /**
     * Set the folder to receive the file
     * @access public
     * @param $uploads_folder String The path of the receive folder
     * @return Void
     */
    public function set_uploads_folder($uploads_folder)
    {
        if(!isset($uploads_folder)) {
            trigger_error('Diretório de destino não especificado', E_USER_ERROR);
        }
        if(!is_dir($uploads_folder)) {
            trigger_error('Diretório de destino não encontrado', E_USER_ERROR);
        }
        
        $this->uploads_folder = $uploads_folder;
    }
    
    /**
     * Set the new name of the file
     * @access public
     * @param $file_name String The new name
     * @return Void
     */
    public function set_file_name($file_name)
    {
        if(!isset($file_name)) {
            $this->file_name = pathinfo($this->file['name'], PATHINFO_FILENAME);
        } else {
            $this->file_name = $file_name;
        }
    }
    
    /**
     * Set the max size of file
     * @access public
     * @param $max_size Double The max size of file
     * @return Void
     */
    public function set_max_size($max_size)
    {
        if(!filter_var($max_size, FILTER_VALIDATE_FLOAT)) {
            trigger_error('O tamanho máximo do arquivo deve ser númerico');
        }
        $this->max_size = $max_size;
    }
    
    /**
     * Set the allowed MIME Types of the upload
     * @access public
     * @param $allowed_types Array
     * @return Void
     */
    public function set_allowed_types(Array $allowed_types)
    {
        $this->allowed_type = $allowed_types;
    }
    
    /**
     * Overwrite file with same name? (true or false)
     * @access public
     * @param $param Boolean Yes(true) or no(false)
     * @return Void
     */
    public function set_overwrite($value)
    {
        $this->overwrite = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    
    /**
     * Get the uploaded file path
     * @access public
     * @return String The uploaded file path
     */
    public function get_file_path()
    {
        if(!isset($this->file_path)) {
            trigger_error('Por favor execute o metodo upload_file primeiro');
        }
        return $this->file_path;
    }    
    
    /**
     * Validates requirements for uploading
     * @access protected
     * @return Boolean True if valid
     */
    public function validate()
    {
        $this->validate_type();
        $this->validate_size();
    }
    
    /**
     * Validate the mimetype of file
     * @access protected
     */
    protected function validate_type()
    {
        if(!isset($this->allowed_type)) {
            return true;
        }
        
        if(!in_array($this->file['type'], $this->allowed_type)) {
            throw new \Exception('O tipo desse arquivo não é permitido pelo sistema');
        }
        
        return true;
    }
    
    
    /**
     * Validate the size of file
     * @access protected
     * @return Boolean True if valid
     */
    protected function validate_size()
    {
        $file_size = $this->file['size'];
        
        /* Convert megabytes to bytes */
        $file_size = ($file_size / 1024) / 1024;
        
        if ($file_size > $this->max_size){
            throw new Exception('O arquivo excedeu o tamanho limite');
        }
        
        return true;
    }
    
    /**
     * Upload the file
     * @access public
     * @return Boolean True if file has been uploaded
     */
    public function upload_file()
    {
        $this->validate();
        
        if(!$this->overwrite) {
            if(file_exists($this->uploads_folder . $this->file_name . '.' . $this->ext)) {
                throw new Exception('O arquivo ' . $this->file_name . '.' . $this->ext . ' já existe na pasta ' . $this->uploads_folder);
            }
        }
        
        if (move_uploaded_file($this->file['tmp_name'], $this->uploads_folder . $this->file_name . '.' . $this->ext)){
            $this->file_path = $this->uploads_folder . $this->file_name . '.' . $this->ext;
            return true;
        }
        
        $this->set_error(5);
        return false;
    }    
}