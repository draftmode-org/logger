<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Formatter\FormatterException;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;

class ArrayFormatterTest extends TestCase {
    private function getRecord() : Record {
        return Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            __NAMESPACE__,
            __METHOD__
        );
    }

    function testFormatExecption() {
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter("d.m.Y", $normalizer);
        $this->expectException(FormatterException::class);
        $formatter->withFormat("test");
    }

    function testNoFormat() {
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter("d.m.Y", $normalizer);
        $this->assertEquals("", $formatter->formatRecord($this->getRecord()));
    }

    function testMethodWithFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $formatter  = (new ArrayFormatter("d.m.Y", $normalizer))->withFormat(["LoggerName", "Level"]);
        $this->assertEquals(join($delimiter, ["loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function testWithoutFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $formatter  = new ArrayFormatter("d.m.Y", $normalizer, ["LoggerName", "Level"]);
        $this->assertEquals(join($delimiter, ["loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function testWithFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $formatter  = new ArrayFormatter("d.m.Y", $normalizer,["LoggerName" =>"{LoggerName}ln:%s", "Level"]);
        $this->assertEquals(join($delimiter, ["ln:loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function testWithConcatFormat() {
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter("d.m.Y", $normalizer, ["LoggerName" =>"{LoggerName}{Level}ln:%s:l:%s"]);
        $this->assertEquals("ln:loggerName:l:".Logger::DEBUG, $formatter->formatRecord($this->getRecord()));
    }
}