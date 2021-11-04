<?php
namespace Terrazza\Component\Logger\Handler;

class StreamHandlerWriteException extends \RuntimeException {
    public function __construct(string $path) {
        parent::__construct(sprintf('cannot write to "%s"', $path));
    }
}