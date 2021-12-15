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
        $normalizer = new NormalizeJson($encodeFlags = JSON_PRETTY_PRINT);
        $token      = [$tKey = "key" => $tValue = "value"];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($tValue, $actual);
    }

    function testConvertTokenValueSingleValueWithFormat() {
        $normalizer = new NormalizeJson($encodeFlags = JSON_PRETTY_PRINT);
        $token      = [$tKey = "key" => $tValue = "value"];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "x%sy");
        $this->assertEquals("x{$tValue}y", $actual);
    }

    function testConvertTokenValueArrayValue() {
        $normalizer = new NormalizeJson($encodeFlags = JSON_PRETTY_PRINT);
        $token      = [$tKey = "key" => $tValue = ["my", "value"]];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue);
        $this->assertEquals($tValue, $actual);
    }

    // Format will be ignored
    function testConvertTokenValueArrayValueWithFormat() {
        $normalizer = new NormalizeJson($encodeFlags = JSON_PRETTY_PRINT);
        $token      = [$tKey = "key" => $tValue = ["my", "value"]];
        $actual     = $normalizer->convertTokenValue($tKey, $tValue, "x%sy");
        $this->assertEquals($tValue, $actual);
    }
}