<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\RecordMocks;
use Terrazza\Component\Logger\Tests\_Mocks\WriterMock;

class ChannelHandlerTest extends TestCase {
    function test() {
        $channel        = new Channel("channel", new WriterMock(), new FormatterMock());
        $handler        = new ChannelHandler($channel);
        $handler->pushHandler(new HandlerPattern(Logger::WARNING), "");
        $emptyHandler   = new ChannelHandler($channel);
        $this->assertEquals([
            false,

            false,
            true,
        ],[
            $emptyHandler->isHandling(RecordMocks::debug()),

            $handler->isHandling(RecordMocks::debug()),
            $handler->isHandling(RecordMocks::warning()),
        ]);
        $emptyHandler->writeRecord(RecordMocks::debug());
        $emptyHandler->close();

        $handler->writeRecord(RecordMocks::warning());
        $handler->close();
    }
}