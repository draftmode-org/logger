<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Log;
use Terrazza\Component\Logger\LogRecord;

class LogRecordTest extends TestCase {

    function testClassCreate() {
        $record = LogRecord::createRecord(
            $loggerName = "loggerName",
            $logLevel = Log::DEBUG,
            $logLevelName = Log::$levels[Log::DEBUG],
            $logMessage = "myMessage",
            [$cKey = "key" => $cValue = "value"]
        );
        $this->assertEquals([
            $loggerName,
            $logLevel,
            $logLevelName,
            $logMessage,
            (new \DateTime())->format("d.m.Y"),

            true,
            false,
            $cValue,
            1,

            null,
            $cValue
        ],[
            $record->getLoggerName(),
            $record->getLogLevel(),
            $record->getLogLevelName(),
            $record->getLogMessage(),
            $record->getLogDate()->format("d.m.Y"),

            $record->hasContextKey($cKey),
            $record->hasContextKey($cKey."unknown"),
            $record->getContextValue($cKey),
            count($record->getContext()),

            $record->shiftContext($cKey."unknown"),
            $record->shiftContext($cKey),
        ]);
    }

}