<?php
namespace Terrazza\Component\Logger\Tests\Formatter;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonConverter;
use Terrazza\Component\Logger\Formatter\LogRecordFormatter;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogRecordValueConverterInterface;
use Terrazza\Component\Logger\Record\LogRecord;

class RecordFormatterTest extends TestCase {
    private function getRecord() : LogRecord {
        return LogRecord::createRecord(
            "loggerName",
            Logger::DEBUG,
            "logMessage",
        );
    }

    function testNoFormat() {
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, []);
        $this->assertEquals([], $formatter->formatRecord($this->getRecord()));
    }

    function testMethodWithFormat() {
        $nonScalar  = new NonScalarJsonConverter();
        $record     = $this->getRecord();
        $formatter  = (new LogRecordFormatter($nonScalar, []))->withFormat(["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithFormat() {
        $nonScalar  = new NonScalarJsonConverter();
        $record     = $this->getRecord();
        $formatter  = new LogRecordFormatter($nonScalar, ["LoggerName", "Level"]);
        $this->assertEquals(
            ["LoggerName" => $record->getLoggerName(), "Level" => $record->getLogLevel()],
            $formatter->formatRecord($record)
        );
    }

    function testWithConverter() {
        $nonScalar  = new NonScalarJsonConverter();
        $record     = $this->getRecord();
        $formatter  = new LogRecordFormatter($nonScalar, ["LoggerName", "Level"]);
        $formatter->pushConverter("LoggerName", new class implements LogRecordValueConverterInterface {
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
        $nonScalar  = new NonScalarJsonConverter();
        $record     = $this->getRecord();
        $formatter  = new LogRecordFormatter($nonScalar, ["LoggerName" =>"ln:{LoggerName}:l:{Level}"]);
        $this->assertEquals(
            ["LoggerName" => "ln:loggerName:l:".Logger::DEBUG],
            $formatter->formatRecord($record)
        );
    }

    function testWithContext() {
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, ["Message" => "{Context.key2}-{Context.key3}-{Context.key7}", "Context", "key1" =>"{Context.key1}"]);
        $record     = LogRecord::createRecord(
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
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, ["Context" => "c:{Context}"]);
        $record     = LogRecord::createRecord(
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
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, ["Message" => "message:{Context.key1}"]);
        $record     = LogRecord::createRecord(
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
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, ["Message" => "message:{Context.key1}"]);
        $record     = LogRecord::createRecord(
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
        $nonScalar  = new NonScalarJsonConverter();
        $formatter  = new LogRecordFormatter($nonScalar, ["Message" => "{Context.key1}-{Context.key2?default}"]);
        $record     = LogRecord::createRecord(
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