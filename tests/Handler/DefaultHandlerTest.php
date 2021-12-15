<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\Handler\DefaultHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogRecord;
use Terrazza\Component\Logger\LogWriterInterface;

class DefaultHandlerTest extends TestCase {
    function test() {
        $channel = new DefaultHandlerTestChannel;
        $handler = new DefaultHandler(Logger::WARNING, $channel);
        $this->assertEquals([
            true,
            false,
            Logger::WARNING,
        ],[
            $handler->isHandling(LogRecord::createRecord("loggerName", Logger::WARNING, "message")),
            $handler->isHandling($record = LogRecord::createRecord("loggerName", Logger::DEBUG, "message")),
            $handler->getLogLevel()
        ]);
        $handler->write($record);
        $handler->close();
    }
}

class DefaultHandlerTestChannel implements ChannelHandlerInterface {

    public function getName(): string {}
    public function getWriter(): LogWriterInterface {}
    public function write(LogRecord $record): void {}
}