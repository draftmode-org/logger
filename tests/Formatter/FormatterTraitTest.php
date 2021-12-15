<?php
namespace Terrazza\Component\Logger\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Formatter\FormatterTrait;

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
}

class FormatterTraitTestTrait {
    use FormatterTrait;
    public function _getTokenValue(array $token, string $findKey) {
        return $this->getTokenValue($token, $findKey);
    }
}