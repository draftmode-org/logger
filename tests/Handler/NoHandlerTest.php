<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\NoHandler;
use Terrazza\Component\Logger\Log;
use Terrazza\Component\Logger\LogRecord;

class NoHandlerTest extends TestCase {
    function testCommon() {
        $record     = LogRecord::createRecord("loggerName", Log::DEBUG, Log::$levels[Log::DEBUG], "myMessage");
        $handler    = new NoHandler();
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