<?php
namespace Terrazza\Component\Logger\Tests\Record;
use DateTime;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\Record\LogRecordTrace;

class LogRecordTest extends TestCase {

    function testClassCreate() {
        $record = new LogRecord(
            $logDate = new DateTime(),
            $loggerName = "loggerName",
            $logLevel = Logger::DEBUG,
            $logMessage = "myMessage",
                $memUsed = 1,
            $memAllocated = 2,
            $trace = new LogRecordTrace(__NAMESPACE__, "-", "-"),
            $context = ["key" => "value"],
            $initContext = ["key" => "value"],
        );
        $dateFormat = "Y-m-d";
        $this->assertEquals([
            $logDate->format($dateFormat),
            $logLevel,
            $logLevelName = Logger::$levels[$logLevel],
            $loggerName,
            $logMessage,
            $memUsed,
            $memAllocated,
            $trace,
            $context,
            $initContext,
            [
                'Date' 				=> $logDate,
                'Level' 			=> $logLevel,
                'LevelName' 		=> $logLevelName,
                'LoggerName' 		=> $loggerName,
                'MemUsed'			=> $memUsed,
                'MemAllocated'		=> $memAllocated,
                'Message' 			=> $logMessage,
                'Context'			=> $context,
                'iContext'			=> $initContext,
                'Trace'			    => [
                    'Namespace'     => __NAMESPACE__,
                    'Line'          => null,
                    'Classname'     => "-",
                    'Function'      => "-",
                    'Method'        => "-::-",
                    'sMethod'       => "-::-"
                ],
            ]
        ],[
            $record->getLogDate()->format($dateFormat),
            $record->getLogLevel(),
            $record->getLogLevelName(),
            $record->getLoggerName(),
            $record->getLogMessage(),
            $record->getMemUsed(),
            $record->getMemAllocated(),
            $record->getTrace(),
            $record->getContext(),
            $record->getInitContext(),
            $record->getToken(),
        ]);
    }
}