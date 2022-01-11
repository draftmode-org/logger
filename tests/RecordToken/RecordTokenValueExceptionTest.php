<?php
namespace Terrazza\Component\Logger\Tests\RecordToken;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Terrazza\Component\Logger\RecordToken\RecordTokenValueException;

class RecordTokenValueExceptionTest extends TestCase {

    function testGetOneLevelNoArgs() {
        $reader     = new RecordTokenValueException(1, false);
        $exception  = new RuntimeException($message = "my");
        $value      =$reader->getValue($exception);
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
}