<?php
namespace Terrazza\Component\Logger\Tests\Writer;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Writer\LogHtmlWriter;

class LogHtmlWriterTest extends TestCase {

    function testSuccessfulWithLineBreak() {
        $writer = new LogHtmlWriter(null, true);
        ob_start();
        $writer->write($message = "myMessage");
        $actual = ob_get_flush();
        $this->assertEquals("$message<br>", $actual);
    }

    function testSuccessfulWithHtmlWrapper() {
        $writer = new LogHtmlWriter("<span>%s</span>");
        ob_start();
        $writer->write($message = "myMessage");
        $actual = ob_get_flush();
        $this->assertEquals("<span>$message</span>", $actual);
    }
}