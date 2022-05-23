<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordFormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordMocks;
use Terrazza\Component\Logger\Tests\_Mocks\LogWriterMock;

class ChannelHandlerTest extends TestCase {
    function test() {
        $channelHandler         = new ChannelHandler(
            $writer = new LogWriterMock(),
            $formatter = new LogRecordFormatterMock(),
            $filter = null,
            $errorHandler = new LogHandler(Logger::ERROR));

        $warningHandler         = new LogHandler(Logger::WARNING);
        $channelHandler->pushLogHandler($warningHandler);

        $this->assertEquals([
            $writer,
            $formatter,
            $filter,
            [$errorHandler->getLogLevel() => $errorHandler, $warningHandler->getLogLevel() => $warningHandler],
            $warningHandler,
            null,
        ],[
            $channelHandler->getWriter(),
            $channelHandler->getFormatter(),
            $channelHandler->getFilter(),
            $channelHandler->getLogHandler(),
            $channelHandler->getEffectedHandler(LogRecordMocks::warning()),
            $channelHandler->getEffectedHandler(LogRecordMocks::debug())
        ]);
    }
}