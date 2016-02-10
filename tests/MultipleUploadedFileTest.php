<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit_Framework_TestCase as PHPUnit;
use \SplFileInfo;
use Alex\Upload\MultipleUploadedFile;

class MultipleUploadedFileTest extends PHPUnit
{
    private $files;
    
    public function setUp()
    {
        $filename = __DIR__ . '/file.txt';
        $handle = fopen($filename, 'w+');
        fclose($handle);
        $file = new SplFileInfo(realpath($filename));
        
        $this->files = array(
            'tmp_name' => array($file->getRealPath(), $file->getRealPath()),
            'name' => array($file->getFilename(), $file->getFilename()),
            'size' => array($file->getSize(), $file->getSize()),
            'error' => array(0, 0), 
            'type' => array('text/plain', 'text/plain'),
        );
        
        unset($file);
        parent::setUp();
    }
    
    public function tearDown()
    {
        //unlink($this->files['tmp_name'][0]);
        unset($this->files);
        parent::tearDown();
    }
    
    public function testTestarInstanciaDaClasse()
    {
        $uploadedFile = new MultipleUploadedFile($this->files, __DIR__);
        $this->assertInstanceOf('Alex\Upload\MultipleUploadedFile', $uploadedFile);
    }
    
    /**
     * @dataProvider provedorDeNomesValidos
     * @param string $nome
     */
    public function testRenomearArquivoComNomesValidos($nome)
    {
        $uploadedFile = new MultipleUploadedFile($this->files, __DIR__);
        //$this->assertNull($uploadedFile->setFileNewName($nome));
    }
    
    public function provedorDeNomesValidos()
    {
        return array(
            array('arquivo'),
            array('arquivo01'),
            array('ação'),
            array('01234'),
            array('teste.txt'),
        );
    }
    
    /**
     * @dataProvider provedorDeNomesInvalidos
     * @expectedException \InvalidArgumentException
     * @param string $nome
     */
    public function testRenomearArquivoComNomesInvalidos($nome)
    {
        $uploadedFile = new MultipleUploadedFile($this->files, __DIR__);
        //$this->assertNull($uploadedFile->setFileNewName($nome));
    }
    
    public function provedorDeNomesInvalidos()
    {
        return array(
            array(''),
            array(null),
        );
    }
    
    public function testMimeTypesPermitidos()
    {
        $uploadedFile = new MultipleUploadedFile($this->files, __DIR__);
        $uploadedFile->setAllowedMimeTypes(array('text/plain'));
        $this->assertTrue($uploadedFile->validate());
        $uploadedFile->setAllowedMimeTypes(array('text/plain', 'image/jpg'));
        $this->assertTrue($uploadedFile->validate());
    }
    
    /**
     * @expectedException \OutOfBoundsException
     */
    public function testMimeTypeInvalido()
    {
        $uploadedFile = new MultipleUploadedFile($this->files, __DIR__);
        $uploadedFile->setAllowedMimeTypes(array('image/png'));
        $this->assertTrue($uploadedFile->validate());
    }
}
