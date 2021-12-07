<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\StreamHandler;
use Terrazza\Component\Logger\Log;
use Terrazza\Component\Logger\LogRecord;

class StreamHandlerTest extends TestCase {
    function testCommon() {
        $record     = LogRecord::createRecord("loggerName", Log::DEBUG, Log::$levels[Log::DEBUG], "myMessage");
        $handler    = new StreamHandler();
        $handler->write($record);
        $this->assertEquals([
            false,
            false,
        ],[
            $handler->isHandling($record),
            $handler->hasLogPatterns()
        ]);
    }
}