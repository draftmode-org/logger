<?php
namespace Terrazza\Component\Logger\Tests\Converter\RecordFormatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordFlatConverter;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordJsonConverter;

class FormattedRecordFlatTest extends TestCase {

    function testCommon() {
        $formatter = new FormattedRecordFlatConverter("|");
        $this->assertEquals(
            "value|".json_encode($data=["key" => "value"]),
            $formatter->convert(["value", "key" => $data])
        );
    }

    function testCommonWithNonScalarPrefix() {
        $formatter = new FormattedRecordFlatConverter("|", JSON_PRETTY_PRINT);
        $formatter->setNonScalarPrefix(PHP_EOL);
        $this->assertEquals(
            "value|key".PHP_EOL.json_encode($data=["key" => "value"], JSON_PRETTY_PRINT),
            $formatter->convert(["value", "key" => $data])
        );
    }
}