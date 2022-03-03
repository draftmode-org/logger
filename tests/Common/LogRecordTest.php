<?php
namespace Terrazza\Component\Logger\Tests\Common;
use DateTime;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record;

class LogRecordTest extends TestCase {

    function testClassCreate() {
        $record = new LogRecordTestRecord(
                $logDate = new DateTime(),
            $loggerName = "loggerName",
            $logLevel = Logger::DEBUG,
            $logMessage = "myMessage",
                $memUsed = 1,
            $memAllocated = 2,
            $context = ["key" => "value"]
        );
        $namespace = __NAMESPACE__;
        $dateFormat = "Y-m-d";
        $this->assertEquals([
            $logDate->format($dateFormat),
            $logLevel,
            $logLevelName = Logger::$levels[$logLevel],
            $loggerName,
            $logMessage,
            $namespace,
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
                'Context'			=> $context
            ]
        ],[
            $record->getLogDate()->format($dateFormat),
            $record->getLogLevel(),
            $record->getLogLevelName(),
            $record->getLoggerName(),
            $record->getLogMessage(),
            $record->getNamespace(),
            $record->getMemUsed(),
            $record->getMemAllocated(),
            $record->getContext(),
            $record->getToken(),
        ]);
    }

}

class LogRecordTestRecord extends Record {
    public function __construct(DateTime $logDate, string $loggerName, int $logLevel, string $logMessage, int $memUsed, int $memAllocated, array $context = null) {
        parent::__construct($logDate, $loggerName, $logLevel, $logMessage, $memUsed, $memAllocated, $context);
    }
}