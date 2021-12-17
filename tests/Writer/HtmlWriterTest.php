<?php
namespace Terrazza\Component\Logger\Tests\Writer;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Writer\HtmlWriter;

class HtmlWriterTest extends TestCase {

    function testSuccessfulWithLineBreak() {
        $writer = new HtmlWriter(null, true);
        ob_start();
        $writer->write($message = "myMessage");
        $actual = ob_get_clean();
        $this->assertEquals("$message<br>", $actual);
    }

    function testSuccessfulWithHtmlWrapper() {
        $writer = new HtmlWriter("<span>%s</span>");
        ob_start();
        $writer->write($message = "myMessage");
        $actual = ob_get_clean();
        $this->assertEquals("<span>$message</span>", $actual);
    }
}