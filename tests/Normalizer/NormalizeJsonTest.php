<?php
namespace Terrazza\Component\Logger\Tests\Normalizer;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Normalizer\NormalizeJson;

class NormalizeJsonTest extends TestCase {

    function testConvertLine() {
        $normalizer = new NormalizeJson($encodeFlags = JSON_PRETTY_PRINT);
        $value      = ["key" => "value"];
        $expected   = json_encode($value, $encodeFlags);
        $actual     = $normalizer->convertLine($value);
        $this->assertEquals($expected, $actual);
    }

    function testConvertTokenValueSingleValue() {
        $normalizer = new NormalizeJson();
        $token      = [$tKey = "key" => $tValue = "value"];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($tValue, $actual);
    }

    function testConvertTokenValueSingleValueWithFormat() {
        $normalizer = new NormalizeJson();
        $token      = [$tKey = "key" => $tValue = "value"];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "x%sy");
        $this->assertEquals("x{$tValue}y", $actual);
    }

    function testConvertTokenValueArrayValue() {
        $normalizer = new NormalizeJson();
        $token      = [$tKey = "key" => $tValue = ["my", "value"]];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($tValue, $actual);
    }

    // Format will be ignored
    function testConvertTokenValueArrayValueWithFormat() {
        $normalizer = new NormalizeJson();
        $token      = [$tKey = "key" => $tValue = ["my", "value"]];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "x%sy");
        $this->assertEquals($tValue, $actual);
    }

    // Format will be ignored
    function testConvertTokenValues() {
        $normalizer = new NormalizeJson();
        $tKey       = "key";
        $tValues    = ["loggerName" => "loggerName", "level" => 2];
        $actual     = $normalizer->convertTokenValues($tKey, $tValues, "ln:%s:l:%s");
        $this->assertEquals($tValues, $actual);
    }

    // single array returns just the singleElement (content is string)
    function testConvertTokenValuesSingle() {
        $normalizer = new NormalizeJson();
        $tKey       = "key";
        $tValues    = ["loggerName" => $tValue = "loggerName"];
        $actual     = $normalizer->convertTokenValues($tKey, $tValues, "%tokenKey:ln:%s:l:%s");
        $this->assertEquals($tValue, $actual);
    }

    // single array returns just the singleElement (content is array)
    function testConvertTokenValuesSingleAsArray() {
        $normalizer = new NormalizeJson();
        $tKey       = "key";
        $tValues    = ["loggerName" => $tValue = ["loggerName"]];
        $actual     = $normalizer->convertTokenValues($tKey, $tValues, "%tokenKey:ln:%s:l:%s");
        $this->assertEquals($tValue, $actual);
    }
}