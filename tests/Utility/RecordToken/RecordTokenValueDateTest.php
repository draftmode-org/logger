<?php
namespace Terrazza\Component\Logger\Tests\Utility\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Utility\RecordValueConverter\LogRecordValueDateConverter;

class RecordTokenValueDateTest extends TestCase {

    function testGet() {
        $reader = new LogRecordValueDateConverter($dateFormat = "Y-m-d");
        $this->assertEquals(
            (new \DateTime())->format($dateFormat),
            $reader->getValue(new \DateTime())
        );
    }
}