<?php
namespace Terrazza\Component\Logger\Tests\Converter\RecordFormatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\FormattedRecord\FormattedRecordJson;

class FormattedRecordJsonTest extends TestCase {

    function testCommon() {
        $formatter = new FormattedRecordJson();
        $this->assertEquals(
            json_encode($data=["key" => "value"]),
            $formatter->convert($data)
        );
    }
}