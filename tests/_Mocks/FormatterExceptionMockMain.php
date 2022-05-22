<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

class FormatterExceptionMockMain {
    public int $exceptionLine=__LINE__+2;
    public function getMainException(int $intValue) : int {
        return $intValue/0;
    }
    public function getExceptionLine() :int {
        return $this->exceptionLine;
    }
}