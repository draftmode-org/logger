<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use RuntimeException;

class FormatterExceptionMockParent2 {
    public int $exceptionLine=__LINE__+4;
    public function getExceptionWith2Parent(int $arg) : int {
        try {
            $parent1Exception = new FormatterExceptionMockParent1();
            return $parent1Exception->getExceptionWith1Parent($arg*2);
        }
        catch (\Throwable $exception) {
            throw new RuntimeException("exception in ".__METHOD__, 0, $exception);
        }
    }
    public function getExceptionLine() :int {
        return $this->exceptionLine;
    }
}