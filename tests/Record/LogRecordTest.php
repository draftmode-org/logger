<?php
namespace Terrazza\Component\Logger\Tests\Record;
use DateTime;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record\LogRecord;

class LogRecordTest extends TestCase {

    function testClassCreate() {
        $record = new LogRecord(
            $logDate = new DateTime(),
            $loggerName = "loggerName",
            $logLevel = Logger::DEBUG,
            $logMessage = "myMessage",
                $memUsed = 1,
            $memAllocated = 2,
            $context = ["key" => "value"]
        );
        $line = __LINE__ - 2;
        $namespace = __NAMESPACE__;
        $dateFormat = "Y-m-d";
        $this->assertEquals([
            $logDate->format($dateFormat),
            $logLevel,
            $logLevelName = Logger::$levels[$logLevel],
            $loggerName,
            $logMessage,
            $memUsed,
            $memAllocated,
            $context,
            [
                'Date' 				=> $logDate,
                'Level' 			=> $logLevel,
                'LevelName' 		=> $logLevelName,
                'LoggerName' 		=> $loggerName,
                'Namespace'			=> $namespace,
                'sNamespace'		=> basename($namespace),
                'MemUsed'			=> $memUsed,
                'MemAllocated'		=> $memAllocated,
                'Message' 			=> $logMessage,
                'Context'			=> $context,
                'Method'            => 'testClassCreate',
                'Line'              => $line
            ]
        ],[
            $record->getLogDate()->format($dateFormat),
            $record->getLogLevel(),
            $record->getLogLevelName(),
            $record->getLoggerName(),
            $record->getLogMessage(),
            $record->getMemUsed(),
            $record->getMemAllocated(),
            $record->getContext(),
            $record->getToken(),
        ]);
    }
}