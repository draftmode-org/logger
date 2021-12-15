<?php
namespace Terrazza\Component\Logger\Tests\Normalizer;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Normalizer\NormalizerTrait;

class NormalizerTraitTest extends TestCase {

    function testConvertValueToString() {
        $trait = new NormalizerTraitTestTrait();
        $this->assertEquals([
            $int=12,
            $string="string",
            $bool=true,
            $null=null,
            json_encode($array=["my" => "value"]),
            json_encode($object=(object)$array),
        ],[
            $trait->_convertValueToString($int),
            $trait->_convertValueToString($string),
            $trait->_convertValueToString($bool),
            $trait->_convertValueToString($null),
            $trait->_convertValueToString($array),
            $trait->_convertValueToString($object),
        ]);
    }

    function testJsonEncodeFailure() {
        $trait = new NormalizerTraitTestTrait();
        $this->assertEquals("#jsonEncodeError:".JSON_ERROR_UTF8."#", $trait->_jsonEncode("\xB1\x31"));
    }
}

class NormalizerTraitTestTrait {
    use NormalizerTrait;

    public function _convertValueToString($value) :?string {
        return $this->convertValueToString($value);
    }

    public function _jsonEncode($value) : string {
        return $this->jsonEncode($value);
    }
}