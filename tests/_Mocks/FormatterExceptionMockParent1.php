<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use RuntimeException;

class FormatterExceptionMockParent1 {
    public int $exceptionLine=__LINE__+4;
    public function getExceptionWith1Parent(int $arg) {
        try {
            $mainException = new FormatterExceptionMockMain();
            $mainException->getMainException($arg*2);
        }
        catch (\Throwable $exception) {
            throw new RuntimeException("exception in ".__METHOD__, 0, $exception);
        }
    }
    public function getExceptionLine() :int {
        return $this->exceptionLine;
    }
}