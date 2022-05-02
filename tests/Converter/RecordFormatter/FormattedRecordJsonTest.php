<?php
namespace Terrazza\Component\Logger\Tests\Converter\RecordFormatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordJsonConverter;

class FormattedRecordJsonTest extends TestCase {

    function testCommon() {
        $formatter = new FormattedRecordJsonConverter();
        $this->assertEquals(
            json_encode($data=["key" => "value"]),
            $formatter->convert($data)
        );
    }
}