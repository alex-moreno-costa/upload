<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit_Framework_TestCase as PHPUnit;
use SplFileInfo;
use Alex\Upload\Upload;

class UploadTest extends PHPUnit
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
    
    public function testTestarInstanciaDaClasse()
    {
        $upload = new Upload($this->files, __DIR__);
        $this->assertInstanceOf('Alex\Upload\Upload', $upload);
    }
    
    /**
     * 
     * @dataProvider namesProvider
     */
    public function testRenomearArquivo($newName)
    {
        $upload = new Upload($this->files, __DIR__);
        $this->assertNull($upload->setFileName($newName));
    }
    
    public function namesProvider()
    {
        return array(
            array('file.txt', null),
            array('novo arquivo.txt', null),
            array('ação.txt', null),
            array('file.xml', null),
            array('file.docx', null),
            array('ARQUIVO.txt', null),
            array('AÇÃO.txt', null),
            array('Qualquer Coisa.txt', null),
        );
    }
    
    public function testCriarDiretorio()
    {
        $dir1 = __DIR__ . '/sub1';
        $dir2 = __DIR__ . '/sub1/sub2/sub3';
        $dir3 = __DIR__ . '/sub1/sub2';
        
        $this->assertFalse(realpath($dir1));
        $this->assertFalse(realpath($dir2));
        
        $upload = new Upload($this->files, __DIR__);
        $this->assertNull($upload->setTargetDirectory($dir1));
        $this->assertNull($upload->setTargetDirectory($dir2));
        
        $this->assertEquals($dir1, realpath($dir1));
        $this->assertEquals($dir2, realpath($dir2));
        
        rmdir($dir2);
        rmdir($dir3);
        rmdir($dir1);
    }
}