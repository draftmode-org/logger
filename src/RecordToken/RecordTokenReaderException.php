<?php
namespace Terrazza\Component\Logger\RecordToken;
use RuntimeException;
use Throwable;

class RecordTokenReaderException extends RuntimeException {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}