<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\RecordMocks;
use Terrazza\Component\Logger\Tests\_Mocks\WriterMock;

class SingleHandlerTest extends TestCase {
    function test() {
        $writer     = new WriterMock();
        $formatter  = new FormatterMock();
        $channel    = new Channel("channel", $writer, $formatter);
        $pattern    = new HandlerPattern(Logger::WARNING);
        $handler    = new SingleHandler($pattern, $channel, "");
        $this->assertEquals([
            true,
            false,
        ],[
            $handler->isHandling(RecordMocks::warning()),
            $handler->isHandling(RecordMocks::debug()),
        ]);
        $handler->writeRecord(RecordMocks::debug());
        $handler->close();
    }
}
