<?php

use Upload\Storage\Storage;
use Upload\Storage\StorageException;

/**
 * Description of StorageTest
 *
 * @author Alex Moreno
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testPerfectScenario()
    {
        $storage = new Storage(__DIR__ . '/../assets/upload_folder');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDiretorioInexistente()
    {
        $storage = new Storage(__DIR__ . '/upload_folder');
    }
    
    public function testCriarDiretorio()
    {
        $diretorio = __DIR__ . '/../assets/teste';
        $storage = new Storage($diretorio, true);
        
        $this->assertTrue(is_dir($diretorio));
        $this->assertEquals(realpath($diretorio), $storage->getDirectory());
        
        rmdir($diretorio);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDiretorioSemPermissaoDeEscrita()
    {
        $storage = new Storage(__DIR__ . '/../assets/without_write_permission');
    }
}