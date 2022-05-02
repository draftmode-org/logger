<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel\Channel;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordFormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordMocks;
use Terrazza\Component\Logger\Tests\_Mocks\LogWriterMock;

class ChannelHandlerTest extends TestCase {
    function test() {
        $channel        = new Channel("channel", new LogWriterMock(), new LogRecordFormatterMock());
        $handler        = new ChannelHandler($channel);
        $handler->pushHandler(Logger::WARNING, []);
        $emptyHandler   = new ChannelHandler($channel);
        $this->assertEquals([
            false,

            false,
            true,
        ],[
            $emptyHandler->isHandling(LogRecordMocks::debug()),

            $handler->isHandling(LogRecordMocks::debug()),
            $handler->isHandling(LogRecordMocks::warning()),
        ]);
        $emptyHandler->writeRecord(LogRecordMocks::debug());
        $emptyHandler->close();

        $handler->writeRecord(LogRecordMocks::warning());
        $handler->close();
    }
}