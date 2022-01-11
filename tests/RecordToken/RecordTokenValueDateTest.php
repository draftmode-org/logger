<?php
namespace Terrazza\Component\Logger\Tests\RecordToken;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\RecordToken\RecordTokenValueDate;

class RecordTokenValueDateTest extends TestCase {

    function testGet() {
        $reader = new RecordTokenValueDate($dateFormat = "Y-m-d");
        $this->assertEquals(
            (new \DateTime())->format($dateFormat),
            $reader->getValue(new \DateTime())
        );
    }
}