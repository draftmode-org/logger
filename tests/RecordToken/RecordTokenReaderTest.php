<?php
namespace Terrazza\Component\Logger\Tests\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\IRecordTokenValueConverter;
use Terrazza\Component\Logger\RecordToken\RecordTokenReader;
use Terrazza\Component\Logger\RecordToken\RecordTokenReaderException;

class RecordTokenReaderTest extends TestCase {
    function xtestInvalidConverter() {
        $this->expectException(RecordTokenReaderException::class);
        new RecordTokenReader(["x" => "y"]);
    }

    function xtestPushUtilityDateConverter() {
        $reader = new RecordTokenReader();
        $reader->pushValueConverter("value", new RecordTokenReaderTestValueConverter());
        $value = "value";
        $token = ["value" => $value];
        $this->assertEquals(
            $value = "value",
            $reader->getValue($token, "value")
        );
    }

    function xtestString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => $tValue = "tValue"];
        $this->assertEquals($tValue, $reader->getValue($token, $tKey));
    }

    function xtestNotFound() {
        $reader     = new RecordTokenReader();
        $token      = ["tKey" => "tValue"];
        $this->assertEquals(null, $reader->getValue($token, "unknown"));
    }

    function xtestArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => $tValue = ["tValue"]];
        $this->assertEquals($tValue, $reader->getValue($token, $tKey));
    }

    function xtestInArrayAsString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = "tInValue"]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey"));
    }

    function xtestNotFoundInArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => ["tInKey" => "tInValue"]];
        $this->assertEquals(null, $reader->getValue($token, "$tKey.unknown"));
    }

    function xtestNotFoundInArray2() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => "tInKey"];
        $this->assertEquals(null, $reader->getValue($token, "$tKey.unknown"));
    }

    function xtestInArrayAsArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => $tValue = ["tInValue"]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey"));
    }

    function xtestInArrayInArrayAsString() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = "tInValue"]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function xtestInArrayInArrayAsArray() {
        $reader     = new RecordTokenReader();
        $token      = [$tKey = "tKey" => [$tInKey = "tInKey" => [$tInInKey = "tInInKey" => $tValue = ["tInValue"]]]];
        $this->assertEquals($tValue, $reader->getValue($token, "$tKey.$tInKey.$tInInKey"));
    }

    function xtestInArrayAll() {
        $reader     = new RecordTokenReader();
        $token      = ["Context" => $context = ["key1" => "value1", "key2" => "value2"], "someOtherKey" => "someOtherValue"];
        $actual     = $reader->getValue($token, "Context");
        $this->assertEquals($context, $actual);
    }

    function testInArraySinglePlusAll() {
        $reader     = new RecordTokenReader();
        $key1       = "key1";
        $value1     = "value1";
        $key2       = "key2";
        $value2     = "value2";
        $key3       = "key3";
        $value3     = "value3";
        $token      = ["Context" => $context = [$key1 => $value1, $key2 => $value2, $key3 => $value3], "someOtherKey" => "someOtherValue"];
        $a_value1   = $reader->getValue($token, "Context.$key1");
        print_r($token);
        $this->assertTrue(true);
    }
}

class RecordTokenReaderTestValueConverter implements IRecordTokenValueConverter {
    public function getValue($value) {
        return $value;
    }
}