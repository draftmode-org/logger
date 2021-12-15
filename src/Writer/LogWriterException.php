<?php
namespace Terrazza\Component\Logger\Writer;

use RuntimeException;

class LogWriterException extends RuntimeException {
    public function __construct(string $path) {
        parent::__construct(sprintf('cannot write to "%s"', $path));
    }
}