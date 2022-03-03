<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
use Terrazza\Component\Logger\Formatter\RecordFormatter;
use Terrazza\Component\Logger\IRecordValueConverter;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record;

class RecordFormatterTest extends TestCase {
    private function getRecord() : Record {
        return Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
        );
    }

    function testNoFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, []);
        $this->assertEquals([], $formatter->formatRecord($this->getRecord()));
    }

    function testMethodWithFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = (new RecordFormatter($nonScalar, []))->withFormat(["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = new RecordFormatter($nonScalar, ["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithConverter() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = new RecordFormatter($nonScalar, ["LoggerName", "Level"]);
        $formatter->pushConverter("LoggerName", new class implements IRecordValueConverter {
            public function getValue($value) : string {
                return "yes";
            }
        });
        $this->assertEquals(
            ["LoggerName" => "yes", "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithConcatFormat() {
        $nonScalar  = new NonScalarJsonEncode();
        $record     = $this->getRecord();
        $formatter  = new RecordFormatter($nonScalar, ["LoggerName" =>"ln:{LoggerName}:l:{Level}"]);
        $this->assertEquals(
            ["LoggerName" => "ln:loggerName:l:".Logger::DEBUG],
            $formatter->formatRecord($record)
        );
    }

    function testWithContext() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, ["Message" => "{Context.key2}-{Context.key3}-{Context.key7}", "Context", "key1" =>"{Context.key1}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            ['key1' => $value1 = 'value1', 'key2' => $value2= 'value2', 'key3' => $value3 = 'value3', $key4 = 'key4' => $value4 = 'value4']
        );
        $this->assertEquals(
            [
                "Message" => "$value2-$value3-", // {Context.key7} does not exist
                "Context" => [$key4 => $value4],
                "key1" => $value1
            ],
            $formatter->formatRecord($record)
        );
    }

    function testWithContextNonScalar() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, ["Context" => "c:{Context}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            $value = ['key1' => 'value1']
        );
        $this->assertEquals(
            [
                "Context" => "c:Context:".json_encode($value),
            ],
            $formatter->formatRecord($record)
        );
    }

    function testWithContextNonScalarArray() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, ["Message" => "message:{Context.key1}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            ['key1' => $value = ["key11" => 'value11']]
        );
        $this->assertEquals(
            [
                "Message" => "message:Context.key1:".json_encode($value),
            ],
            $formatter->formatRecord($record)
        );
    }

    function testWithContextNonScalarObject() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, ["Message" => "message:{Context.key1}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
            ['key1' => $key1Value = (object)["key11" => 'value11']]
        );
        $this->assertEquals(
            [
                "Message" => "message:Context.key1:".json_encode($key1Value),
            ],
            $formatter->formatRecord($record)
        );
    }

    function testWithContextDefault() {
        $nonScalar  = new NonScalarJsonEncode();
        $formatter  = new RecordFormatter($nonScalar, ["Message" => "{Context.key1}-{Context.key2?default}"]);
        $record     = Record::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
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