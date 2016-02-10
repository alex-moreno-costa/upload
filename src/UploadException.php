<?php

namespace Alex\Upload;

use Alex\Upload\UploadConfig;

/**
 * Description of UploadException
 *
 * @author Alex Moreno
 */
class UploadException extends \Exception
{

    private $config;

    public function __construct($code)
    {
        $this->config = new UploadConfig;
        $message = $this->getErrorMessage($code);
        parent::__construct($message, $code, null);
    }

    private function getErrorMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return sprintf('O tamanho do arquivo é maior do que o permitido pelo sistema (%s)', $this->config->getUploadMaxFileSize());
                break;
            case UPLOAD_ERR_FORM_SIZE:
                return sprintf('O tamanho do arquivo é maior do que é permitido pelo formulário HTML');
                break;
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                return 'Nenhum arquivo foi enviado para upload';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                return sprintf('O diretório temporario "%s" não foi encontrado.', $this->config->getUploadTmpDir());
                break;
            case UPLOAD_ERR_CANT_WRITE:
                return sprintf('Não foi possível salvar o arquivo em "%s"', '');
                break;
            case UPLOAD_ERR_EXTENSION:
                return 'Alguma extensão do PHP interrompeu o upload do arquivo';
                break;
            case UPLOAD_ERR_OK:
                /* upload sem erro */
                break;

            default:
                return 'Erro desconhecido';
                break;
        }
    }

}
