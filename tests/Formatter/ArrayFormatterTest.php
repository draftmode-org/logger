<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Formatter\FormatterException;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;
use Terrazza\Component\Logger\RecordToken\RecordTokenReader;

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

    function xtestFormatException() {
        $normalizer = new NormalizeFlat("|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer);
        $this->expectException(FormatterException::class);
        $formatter->withFormat("test");
    }

    function xtestNoFormat() {
        $normalizer = new NormalizeFlat("|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer);
        $this->assertEquals("", $formatter->formatRecord($this->getRecord()));
    }

    function xtestMethodWithFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $tokenReader= new RecordTokenReader();
        $formatter  = (new ArrayFormatter($tokenReader, $normalizer))->withFormat(["LoggerName", "Level"]);
        $this->assertEquals(join($delimiter, ["loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function xtestWithoutFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer, ["LoggerName", "Level"]);
        $this->assertEquals(join($delimiter, ["loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function xtestWithFormat() {
        $normalizer = new NormalizeFlat($delimiter = "|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer,["LoggerName" =>"ln:{LoggerName}", "Level"]);
        $this->assertEquals(join($delimiter, ["ln:loggerName", Logger::DEBUG]), $formatter->formatRecord($this->getRecord()));
    }

    function xtestWithConcatFormat() {
        $normalizer = new NormalizeFlat("|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer, ["LoggerName" =>"ln:{LoggerName}:l:{Level}"]);
        $this->assertEquals("ln:loggerName:l:".Logger::DEBUG, $formatter->formatRecord($this->getRecord()));
    }

    function testWithContext() {
        $normalizer = new NormalizeFlat("|");
        $tokenReader= new RecordTokenReader();
        $formatter  = new ArrayFormatter($tokenReader, $normalizer, ["2nd" => "{Context.key2}-{Context.key3}", "Context", "key1" =>"{Context.key1}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            __NAMESPACE__,
            __METHOD__,
            ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3', 'key4' => 'value4']
        );
        $response   = $formatter->formatRecord($record);
        print_r(PHP_EOL.$response);
        $this->assertTrue(true);
    }
}