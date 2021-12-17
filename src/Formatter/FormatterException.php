<?php
namespace Terrazza\Component\Logger\Formatter;
use RuntimeException;
use Throwable;

class FormatterException extends RuntimeException {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}