<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use RuntimeException;

class FormatterExceptionMockMain {
    public int $exceptionLine=__LINE__+2;
    public function getMainException(int $arg) {
        throw new RuntimeException("exception in ".__METHOD__);
    }
    public function getExceptionLine() :int {
        return $this->exceptionLine;
    }
}