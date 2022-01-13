<?php
namespace Terrazza\Component\Logger\Tests\Utility\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\RecordToken\RecordTokenReader;
use Terrazza\Component\Logger\Utility\RecordToken\RecordTokenValueDate;
use Terrazza\Component\Logger\Utility\RecordToken\RecordTokenValueException;

class RecordTokenReaderTest extends TestCase {

    function testWithConverter() {
        $reader = new RecordTokenReader([
            "Date" => new RecordTokenValueDate($dateFormat = "d.m.Y")
        ]);
        $reader->pushValueConverter("Exception", new RecordTokenValueException());
        $this->assertEquals(
            (new \DateTime())->format($dateFormat),
            $reader->getValue(["Date" => new \DateTime()], "Date")
        );
    }
}