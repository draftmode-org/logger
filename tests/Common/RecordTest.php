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
            $namespace = __NAMESPACE__,
            $method = __METHOD__,
            $context = ["key" => "value"]
        );
        $dateFormat = "Y-m-d";
        $this->assertEquals([
            $logDate->format($dateFormat),
            $logLevel,
            $logLevelName = Logger::$levels[$logLevel],
            $loggerName,
            $logMessage,
            $namespace,
            $method,
            $memUsed,
            $memAllocated,
            $context,
            [
                'Date' 				=> (new \DateTime)->format($dateFormat),
                'Level' 			=> $logLevel,
                'LevelName' 		=> $logLevelName,
                'LoggerName' 		=> $loggerName,
                'Namespace'			=> $namespace,
                'sNamespace'		=> basename($namespace),
                'Method'			=> $method,
                'sMethod'			=> basename($method),
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
            $record->getMethod(),
            $record->getMemUsed(),
            $record->getMemAllocated(),
            $record->getContext(),
            $record->getToken($dateFormat),
        ]);
    }

}

class LogRecordTestRecord extends Record {
    public function __construct(DateTime $logDate, string $loggerName, int $logLevel, string $logMessage, int $memUsed, int $memAllocated, string $namespace = null, string $method = null, array $context = null) {
        parent::__construct($logDate, $loggerName, $logLevel, $logMessage, $memUsed, $memAllocated, $namespace, $method, $context);
    }
}