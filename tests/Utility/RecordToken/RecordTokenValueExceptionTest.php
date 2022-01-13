<?php
namespace Terrazza\Component\Logger\Tests\Utility\RecordToken;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockMain;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockParent1;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockParent2;
use Terrazza\Component\Logger\Utility\RecordToken\RecordTokenValueException;

class RecordTokenValueExceptionTest extends TestCase {

    function testGetOneLevelNoArgs() {
        $reader     = new RecordTokenValueException(1, false);
        $exception  = new RuntimeException($message = "my");
        $value      = $reader->getValue($exception);
        $this->assertEquals(
            [
                $message,
                TestCase::class,
            ], [
                $value[0]["message"],
                $value[0]["class"],
            ]
        );
    }

    function testExceptionWith1Parent() {
        $parent1Class   = new FormatterExceptionMockParent1;
        try {
            $line       = __LINE__ + 1;
            $parent1Class->getExceptionWith1Parent(12);
        } catch (\Throwable $exception) {}
        $traceCount     = 3;
        $reader         = new RecordTokenValueException($traceCount, false);
        $trace          = $reader->getValue($exception);
        $this->assertEquals([
            $traceCount,
            $line,
            $parent1Class->getExceptionLine(),
            (new FormatterExceptionMockMain)->getExceptionLine(),
        ],[
            count($trace),
            $trace[0]["line"],
            $trace[1]["line"],
            $trace[2]["line"]
        ]);
    }

    function testExceptionWith2Parents() {
        $parent2Class   = new FormatterExceptionMockParent2;
        try {
            $line       = __LINE__ + 1;
            $parent2Class->getExceptionWith2Parent($arg = 12);
        } catch (\Throwable $exception) {}
        $traceCount     = 4;
        $reader         = new RecordTokenValueException($traceCount, true);
        $trace          = $reader->getValue($exception);
        $this->assertEquals([
            $traceCount,
            $line,
            $parent2Class->getExceptionLine(),
            (new FormatterExceptionMockParent1)->getExceptionLine(),
            (new FormatterExceptionMockMain)->getExceptionLine(),

            [$arg],
            [$arg*2],
            [$arg*4],
            [$arg*4],
        ],[
            count($trace),
            $trace[0]["line"],
            $trace[1]["line"],
            $trace[2]["line"],
            $trace[3]["line"],

            $trace[0]["args"],
            $trace[1]["args"],
            $trace[2]["args"],
            $trace[3]["args"],
        ]);
    }
}