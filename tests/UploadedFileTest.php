<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit_Framework_TestCase as PHPUnit;
use Alex\Upload\UploadedFile;
use Alex\Upload\UploadException;

class UploadedFileTest extends PHPUnit
{
    private $files;
    
    public function setUp()
    {
        $filename = __DIR__ . '/example-file/file.txt';
        $handle = fopen($filename, 'w+');
        fclose($handle);
        $file = new SplFileInfo(realpath($filename));
        
        $this->files = array(
            'tmp_name' => $file->getRealPath(),
            'name' => $file->getFilename(),
            'size' => $file->getSize(),
            'error' => 0, 
            'type' => 'text/plain'
        );
        
        unset($file);
        parent::setUp();
    }
    
    public function tearDown()
    {
        unlink($this->files['tmp_name']);
        unset($this->files);
        parent::tearDown();
    }
    
    public function testTestarInstanciaDaClasse()
    {
        $uploadedFile = new UploadedFile($this->files);
        $this->assertInstanceOf('Alex\Upload\UploadedFile', $uploadedFile);
    }
    
    public function testValidarRetornoDosMetodos()
    {
        $uploadedFile = new UploadedFile($this->files);
        $this->assertEquals($this->files['name'], $uploadedFile->getFileName());
        $this->assertEquals($this->files['tmp_name'], $uploadedFile->getTemporaryFile());
        $this->assertEquals($this->files['size'], $uploadedFile->getFileSize());
        $this->assertEquals($this->files['type'], $uploadedFile->getFileMimeType());
    }
    
    /**
     * @dataProvider provedorDeErro
     * @expectedException Alex\Upload\UploadException
     */
    public function testLançamentoDeErros($numeroDoErro)
    {
        $this->files['error'] = $numeroDoErro;
        $uploadedFile = new UploadedFile($this->files);
    }
    
    public function provedorDeErro()
    {
        $array = array();
        for ($i = 1; $i <= 8; $i++) {
            array_push($array, $i);
        }
        return array($array);
    }
}