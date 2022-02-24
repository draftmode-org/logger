<?php
namespace Terrazza\Component\Logger\Tests\Converter\RecordFormatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordFlat;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordJson;

class FormattedRecordFlatTest extends TestCase {

    function testCommon() {
        $formatter = new FormattedRecordFlat("|");
        $this->assertEquals(
            "value|".json_encode($data=["key" => "value"]),
            $formatter->convert(["value", "key" => $data])
        );
    }

    function testCommonWithNonScalarPrefix() {
        $formatter = new FormattedRecordFlat("|", JSON_PRETTY_PRINT);
        $formatter->setNonScalarPrefix(PHP_EOL);
        $this->assertEquals(
            "value|key".PHP_EOL.json_encode($data=["key" => "value"], JSON_PRETTY_PRINT),
            $formatter->convert(["value", "key" => $data])
        );
    }
}