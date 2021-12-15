<?php
namespace Terrazza\Component\Logger\Tests\Normalizer;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;

class NormalizeFlatTest extends TestCase {

    function testConvertLine() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $value      = ["key" => "value"];
        $expected   = join($delimiter, $value);
        $actual     = $normalizer->convertLine($value);
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValue() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValue     = "value";
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($tValue, $actual);
    }

    function testConvertTokenValueWithFormat() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValue     = "value";
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "x%sy");
        $this->assertEquals("x{$tValue}y", $actual);
    }

    function testConvertTokenValueArrayValue() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValue     = ["my", "value"];
        $expected   = json_encode($tValue);
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValueArrayValueWithFormat() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValue     = ["my", "value"];
        $expected   = "$tKey:".json_encode($tValue);
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "%tokenKey:%s");
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValueObjectValueWithFormat() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValue     = (object)["property" => "value"];
        $expected   = "$tKey:".json_encode($tValue);
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "%tokenKey:%s");
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValues() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValues    = ["loggerName" => $loggerName = "loggerName", "level" => $level=2];
        $expected   = "ln:$loggerName:l:$level";
        $actual     = $normalizer->convertTokenValues($tKey, $tValues, "ln:%s:l:%s");
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValuesWithoutFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $tKey       = "key";
        $tValues    = ["loggerName" => $loggerName = "loggerName", "level" => $level=2];
        $expected   = "$loggerName$delimiter$level";
        $actual     = $normalizer->convertTokenValues($tKey, $tValues);
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValuesWithTokenKey() {
        $normalizer = new NormalizeFlat("|");
        $tKey       = "key";
        $tValues    = ["loggerName" => $loggerName = "loggerName", "level" => $level=2];
        $expected   = "$tKey:ln:$loggerName:l:$level";
        $actual     = $normalizer->convertTokenValues($tKey, $tValues, "%tokenKey:ln:%s:l:%s");
        $this->assertEquals($expected, $actual);
    }
}