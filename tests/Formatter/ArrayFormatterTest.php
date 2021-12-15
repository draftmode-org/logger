<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogRecord;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;

class ArrayFormatterTest extends TestCase {
    private function getRecord() : LogRecord {
        return LogRecord::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            __NAMESPACE__,
            __METHOD__
        );
    }

    function xtestNormalizeFlatEmpty() {
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter([], "d.m.Y", $normalizer);
        $this->assertEquals("", $formatter->format($this->getRecord()));
    }

    function xtestNormalizeFlatWithoutFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $formatter  = new ArrayFormatter(["LoggerName", "Level"], "d.m.Y", $normalizer);
        $this->assertEquals(join($delimiter, ["loggerName", Logger::DEBUG]), $formatter->format($this->getRecord()));
    }

    function testNormalizeFlatWithFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $formatter  = new ArrayFormatter(["LoggerName" =>"{LoggerName}ln:%s", "Level"], "d.m.Y", $normalizer);
        $this->assertEquals(join($delimiter, ["ln:loggerName", Logger::DEBUG]), $formatter->format($this->getRecord()));
    }

    function testNormalizeFlatWithConcatFormat() {
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter(["LoggerName" =>"{LoggerName}{Level}ln:%s:l:%s"], "d.m.Y", $normalizer);
        $this->assertEquals("ln:loggerName:l:".Logger::DEBUG, $formatter->format($this->getRecord()));
    }
}