<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record\LogRecord;

class LogRecordMocks {
    public static function debug() : LogRecord {
        return LogRecord::createRecord("loggerName", Logger::DEBUG, "message");
    }
    public static function warning() : LogRecord {
        return LogRecord::createRecord("loggerName", Logger::WARNING, "message");
    }
}