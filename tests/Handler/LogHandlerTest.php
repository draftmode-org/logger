<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordMocks;

class LogHandlerTest extends TestCase {
    function test() {
        $handler    = new LogHandler(Logger::WARNING);
        $this->assertEquals([
            Logger::WARNING,
            null,
            null,

            true,
            false,
        ],[
            $handler->getLogLevel(),
            $handler->getFilter(),
            $handler->getFormat(),

            $handler->isHandling(LogRecordMocks::warning()),
            $handler->isHandling(LogRecordMocks::debug()),
        ]);
    }
}
