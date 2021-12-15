<?php
namespace Terrazza\Component\Logger\Tests\Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\ChannelHandler;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogRecord;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;
use Terrazza\Component\Logger\Writer\LogStreamWriter;

class LogExampleTest extends TestCase {
    public string $stream="tests/Examples/stream.txt";
    function setUp(): void {
        @unlink($this->stream);
    }
    function tearDown(): void {
        @unlink($this->stream);
    }

    function testStream() {
        $record = LogRecord::createRecord(
            $loggerName = "loggerName",
            $logLevel = Logger::DEBUG,
            $logMessage = "logMessage",
            $namespace = __NAMESPACE__,
            $method = __METHOD__,
            $context = [$cKey = "key" => $cValue = "value"]
        );
        $writer     = new LogStreamWriter($this->stream);
        $normalizer = new NormalizeFlat("|");
        $formatter  = new ArrayFormatter(["logLevel"], "Y-m-d", $normalizer);
        $channel    = new ChannelHandler("channel", $writer, $formatter);
        $channel->write($record);
        var_dump(file_get_contents($this->stream));
        $this->assertTrue(true);
    }
}