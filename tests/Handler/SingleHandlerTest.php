<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel\Channel;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordFormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordMocks;
use Terrazza\Component\Logger\Tests\_Mocks\LogWriterMock;

class SingleHandlerTest extends TestCase {
    function test() {
        $writer     = new LogWriterMock();
        $formatter  = new LogRecordFormatterMock();
        $channel    = new Channel("channel", $writer, $formatter);
        $handler    = new LogHandler(Logger::WARNING, $channel, []);
        $this->assertEquals([
            true,
            false,
        ],[
            $handler->isHandling(LogRecordMocks::warning()),
            $handler->isHandling(LogRecordMocks::debug()),
        ]);
        $handler->writeRecord(LogRecordMocks::debug());
        $handler->close();
    }
}
