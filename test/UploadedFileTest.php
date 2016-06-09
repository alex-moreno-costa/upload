<?php

use Upload\UploadedFile;

/**
 * Description of UploadedFileTest
 *
 * @author Alex Moreno
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    private $txt;
    private $doc;
    
    public function setUp()
    {
        $txtpath = __DIR__ . '/assets/dumpfiles/file.txt';
        $docpath = __DIR__ . '/assets/dumpfiles/file.doc';
        
        $txt = array(
            'tmp_name' => realpath($txtpath),
            'type' => mime_content_type($txtpath),
            'size' => filesize($txtpath),
            'name' => pathinfo($txtpath, PATHINFO_BASENAME),
        );
        $this->txt = $txt;
        
        $doc = array(
            'tmp_name' => realpath($docpath),
            'type' => mime_content_type($docpath),
            'size' => filesize($docpath),
            'name' => pathinfo($docpath, PATHINFO_BASENAME),
        );
        $this->doc = $doc;
    }
    
    public function testVerificarArquivoTXT()
    {
        $upload = new UploadedFile($this->txt);
        
        $this->assertInstanceOf('\SplFileInfo', $upload);
        $this->assertEquals(filesize($this->txt['tmp_name']), $upload->getSize());
        $this->assertEquals(md5_file($this->txt['tmp_name']), $upload->getMd5());
        $this->assertEquals($this->txt['name'], $upload->getBasename());
        $this->assertEquals(pathinfo($this->txt['name'], PATHINFO_FILENAME), $upload->getFilename());
        $this->assertEquals($this->txt['type'], $upload->getMimeType());
        $this->assertEquals('txt', $upload->getExtension());
        
        
        $new_name = 'abc';
        $upload->setFilename($new_name);
        $this->assertEquals($new_name . '.txt', $upload->getBasename());
        $this->assertEquals($new_name, $upload->getFilename());
    }
    
    public function testVerificarArquivoDOC()
    {
        $upload = new UploadedFile($this->doc);
        
        $this->assertInstanceOf('\SplFileInfo', $upload);
        $this->assertEquals(filesize($this->doc['tmp_name']), $upload->getSize());
        $this->assertEquals(md5_file($this->doc['tmp_name']), $upload->getMd5());
        $this->assertEquals($this->doc['name'], $upload->getBasename());
        $this->assertEquals(pathinfo($this->doc['name'], PATHINFO_FILENAME), $upload->getFilename());
        $this->assertEquals($this->doc['type'], $upload->getMimeType());
        $this->assertEquals('doc', $upload->getExtension());
        
        $new_name = 'abc';
        $upload->setFilename($new_name);
        $this->assertEquals($new_name . '.doc', $upload->getBasename());
        $this->assertEquals($new_name, $upload->getFilename());
    }
}
