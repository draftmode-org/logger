<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\Record\LogRecordTrace;

class LogRecordMocks {
    public static function debug() : LogRecord {
        return LogRecord::createRecord("loggerName", Logger::DEBUG, "message", new LogRecordTrace(__NAMESPACE__, "-", "-", 0));
    }
    public static function warning() : LogRecord {
        return LogRecord::createRecord("loggerName", Logger::WARNING, "message", new LogRecordTrace(__NAMESPACE__, "-", "-", 0));
    }
}