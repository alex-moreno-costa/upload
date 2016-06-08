<?php

namespace Upload;

/**
 *
 * @author Daniel
 */
interface FileInfoInterface
{
    public function getMd5();
    public function getMimeType();
}
