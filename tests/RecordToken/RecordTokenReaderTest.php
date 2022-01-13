<?php
namespace Terrazza\Component\Logger\Tests\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\IRecordTokenValueConverter;
use Terrazza\Component\Logger\RecordToken\RecordTokenReader;
use Terrazza\Component\Logger\RecordToken\RecordTokenReaderException;

class RecordTokenReaderTest extends TestCase {
    function testInvalidConverter() {
        $this->expectException(RecordTokenReaderException::class);
        new RecordTokenReader(["x" => "y"]);
    }

    function testPushUtilityDateConverter() {
        $reader = new RecordTokenReader();
        $reader->pushValueConverter("value", new RecordTokenReaderTestValueConverter());
        $this->assertEquals(
            $value = "value",
            $reader->getValue(["value" => $value], "value")
        );
    }

    function testString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => $tValue = "tValue"];
        $this->assertEquals($tValue, $reader->getValue($token, $tKey));
    }

    function testNotFound() {
        $reader     = new RecordTokenReader();
        $token      = ["tKey" => "tValue"];
        $this->assertEquals(null, $reader->getValue($token, "unknown"));
    }

    function testArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => $tValue = ["tValue"]];
        $this->assertEquals($tValue, $reader->getValue($token, $tKey));
    }

    function testInArrayAsString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = "tInValue"]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey"));
    }

    function testNotFoundInArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => ["tInKey" => "tInValue"]];
        $this->assertEquals(null, $reader->getValue($token, "$tKey.unknown"));
    }

    function testNotFoundInArray2() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => "tInKey"];
        $this->assertEquals(null, $reader->getValue($token, "$tKey.unknown"));
    }

    function testInArrayAsArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = ["tInValue"]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey"));
    }

    function testInArrayInArrayAsString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = "tInValue"]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function testInArrayInArrayAsArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = ["tInValue"]]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function testInArrayAll() {
        $reader     = new RecordTokenReader();
        $token      = ["Context" => $context = ["key1" => "value1", "key2" => "value2"], "someOtherKey" => "someOtherValue"];
        $actual     = $reader->getValue($token, "Context.*");
        $this->assertEquals($context, $actual);
    }
}

class RecordTokenReaderTestValueConverter implements IRecordTokenValueConverter {
    public function getValue($value) {
        return $value;
    }
}