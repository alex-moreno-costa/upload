<?php

use Upload\UploadedFile;

/**
 * Description of UploadedFileTest
 *
 * @author Alex Moreno
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    private $file;
    
    public function setUp()
    {
        $filepath = __DIR__ . '/assets/dumpfiles/file.txt';
        
        $file = array(
            'tmp_name' => realpath($filepath),
            'type' => 'text/plain',
            'size' => filesize($filepath),
            'name' => pathinfo($filepath, PATHINFO_FILENAME),
        );
        
        $this->file = $file;
    }
    
    
    public function testVerificarInstancia()
    {
        $upload = new UploadedFile($this->file);
        
        $this->assertInstanceOf('\SplFileInfo', $upload);
        $this->assertEquals(filesize($this->file['tmp_name']), $upload->getSize());
    }
}
