<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Record;

class RecordMocks {
    public static function debug() : Record {
        return Record::createRecord("loggerName", Logger::DEBUG, "message");
    }
    public static function warning() : Record {
        return Record::createRecord("loggerName", Logger::WARNING, "message");
    }
}