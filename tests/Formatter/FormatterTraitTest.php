<?php
namespace Terrazza\Component\Logger\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\RuntimeException;
use Terrazza\Component\Logger\Formatter\FormatterTrait;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockMain;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockParent1;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterExceptionMockParent2;

class FormatterTraitTest extends TestCase {

    function testString() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => $tValue = "tValue"];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, $tKey));
    }

    function testNotFound() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = ["tKey" => "tValue"];
        $this->assertEquals(null, $formatter->_getTokenValue($token, "unknown"));
    }

    function testArray() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => $tValue = ["tValue"]];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, $tKey));
    }

    function testInArrayAsString() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = "tInValue"]];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, "$tKey.$tInKey"));
    }


    function testNotFoundInArray() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => ["tInKey" => "tInValue"]];
        $this->assertEquals(null, $formatter->_getTokenValue($token, "$tKey.unknown"));
    }

    function testInArrayAsArray() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = ["tInValue"]]];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, "$tKey.$tInKey"));
    }

    function testInArrayInArrayAsString() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = "tInValue"]]];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function testInArrayInArrayAsArray() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = ["tInValue"]]]];
        $this->assertEquals($tValue, $formatter->_getTokenValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function testInArrayAll() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = ["Context" => $context = ["key1" => "value1", "key2" => "value2"], "someOtherKey" => "someOtherValue"];
        $actual     = $formatter->_getTokenValue($token, "Context.*");
        $this->assertEquals($context, $actual);
    }

    function xtestException() {
        $formatter  = new FormatterTraitTestTrait();
        $token      = ["exception" => new RuntimeException("myException")];
        $trace      = $formatter->_getTokenValue($token, "exception");
        $this->assertCount(10, $trace);
    }

    public int $exceptionLine=0;
    private function getException(int $arg) {
        $this->exceptionLine = __LINE__ + 1;
        throw new RuntimeException("exception in ".__METHOD__);
    }

    public int $exception1ParentLine=0;
    private function getExceptionWith1Parent(int $arg) {
        try {
            $this->exception1ParentLine = __LINE__ + 1;
            $this->getException($arg*2);
        }
        catch (\Throwable $exception) {
            throw new RuntimeException("exception in ".__METHOD__, 0, $exception);
        }
    }

    public int $exception2ParentLine=0;
    private function getExceptionWith2Parents(int $arg) {
        try {
            $this->exception2ParentLine = __LINE__ + 1;
            $this->getExceptionWith1Parent($arg*2);
        }
        catch (\Throwable $exception) {
            throw new RuntimeException("exception in ".__METHOD__, 0, $exception);
        }
    }

    function xtestExceptionLevel3() {
        $formatter      = new FormatterTraitTestTrait();
        $token          = [];
        try {
            $this->getException(12);
        } catch (\Throwable $exception) {
            $token      = ["exception" => $exception];
        }
        $traceCount = 3;
        $trace      = $formatter->_getTokenValue($token, "exception.".$traceCount.".2");
        $this->assertCount($traceCount, $trace);
    }

    function testExceptionWith1Parent() {
        $formatter      = new FormatterTraitTestTrait();
        $token          = [];
        $parent1Class   = new FormatterExceptionMockParent1;
        try {
            $line       = __LINE__ + 1;
            $parent1Class->getExceptionWith1Parent(12);
        } catch (\Throwable $exception) {
            $token      = ["exception" => $exception];
        }
        $traceCount = 3;
        $trace      = $formatter->_getTokenValue($token, "exception.".$traceCount.".2");
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
        $formatter      = new FormatterTraitTestTrait();
        $token          = [];
        $parent2Class   = new FormatterExceptionMockParent2;
        try {
            $line       = __LINE__ + 1;
            $parent2Class->getExceptionWith2Parent($arg = 12);
        } catch (\Throwable $exception) {
            $token      = ["exception" => $exception];
        }
        $traceCount     = 4;
        $trace          = $formatter->_getTokenValue($token, "exception.".$traceCount.".1");
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

class FormatterTraitTestTrait {
    use FormatterTrait;
    public function _getTokenValue(array $token, string $findKey) {
        return $this->getTokenValue($token, $findKey);
    }
}