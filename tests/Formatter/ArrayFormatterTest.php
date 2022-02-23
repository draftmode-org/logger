<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
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

    function testNoFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new ArrayFormatter($nonScalar, []);
        $this->assertEquals([], $formatter->formatRecord($this->getRecord()));
    }

    function testMethodWithFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = (new ArrayFormatter($nonScalar, []))->withFormat(["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = new ArrayFormatter($nonScalar, ["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithConcatFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = new ArrayFormatter($nonScalar, ["LoggerName" =>"ln:{LoggerName}:l:{Level}"]);
        $this->assertEquals(
            ["LoggerName" => "ln:loggerName:l:".Logger::DEBUG],
            $formatter->formatRecord($record)
        );
    }

    function testWithContext() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new ArrayFormatter($nonScalar, ["Message" => "{Context.key2}-{Context.key3}-{Context.key7}", "Context", "key1" =>"{Context.key1}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            __NAMESPACE__,
            __METHOD__,
            ['key1' => $value1 = 'value1', 'key2' => $value2= 'value2', 'key3' => $value3 = 'value3', $key4 = 'key4' => $value4 = 'value4']
        );
        $this->assertEquals(
            [
                "Message" => "$value2-$value3-", // {Context.key7} does not exists
                "Context" => [$key4 => $value4],
                "key1" => $value1
            ],
            $formatter->formatRecord($record)
        );
    }

    function testWithContextDefault() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new ArrayFormatter($nonScalar, ["Message" => "{Context.key1}-{Context.key2?default}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            __NAMESPACE__,
            __METHOD__,
            ['key1' => $value1 = 'value1']
        );
        $this->assertEquals(
            [
                "Message" => "$value1-default",
            ],
            $formatter->formatRecord($record)
        );
    }
}