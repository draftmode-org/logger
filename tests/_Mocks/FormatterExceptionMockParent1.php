<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use RuntimeException;

class FormatterExceptionMockParent1 {
    public int $exceptionLine=__LINE__+5;
    public int $throwLine=__LINE__+7;
    public function getExceptionWith1Parent(int $arg) : int {
        try {
            $mainException = new FormatterExceptionMockMain();
            return $mainException->getMainException($arg*2);
        }
        catch (\Throwable $exception) {
            throw new RuntimeException("exception in ".__METHOD__, 0, $exception);
        }
    }
    public function getExceptionLine() :int {
        return $this->exceptionLine;
    }
    public function getThrowLine() : int {
        return $this->throwLine;
    }
    public function createException(int $arg) : int {
        return $arg/0;
    }
}