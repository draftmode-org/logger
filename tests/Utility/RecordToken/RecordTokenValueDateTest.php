<?php
namespace Terrazza\Component\Logger\Tests\Utility\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Utility\RecordValueConverter\RecordValueDate;

class RecordTokenValueDateTest extends TestCase {

    function testGet() {
        $reader = new RecordValueDate($dateFormat = "Y-m-d");
        $this->assertEquals(
            (new \DateTime())->format($dateFormat),
            $reader->getValue(new \DateTime())
        );
    }
}