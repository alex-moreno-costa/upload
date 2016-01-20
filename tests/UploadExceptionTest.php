<?php

require __DIR__ . '/../vendor/autoload.php';

use Alex\Upload\UploadException;
use PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of UploadExceptionTest
 *
 * @author Alex Moreno
 */
class UploadExceptionTest extends PHPUnit
{
    /**
     * @expectedException UploadException
     */
    public function testReturnOfException()
    {
        $this->assertEquals(1, 1);
    }
}
