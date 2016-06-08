<?php

namespace Upload\Validation;

use Upload\FileInfoInterface;

/**
 *
 * @author Daniel
 */
interface ValidationInterface
{
    public function validate(FileInfoInterface $file);
}
