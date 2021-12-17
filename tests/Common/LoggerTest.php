<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\HandlerInterface;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\WriterMock;

class LoggerTest extends TestCase {
    private function getEmptyHandler(int $logLevel) : HandlerInterface {
        return new SingleHandler(
            new HandlerPattern($logLevel),
            new Channel(
                "channel",
                new WriterMock(),
                new FormatterMock()
            ),
            ""
        );
    }

    function testMethods() {
        $logger = new Logger("loggerName", ["k" => "v"]);
        $logger->emergency("message");
        $logger->alert("message");
        $logger->critical("message");
        $logger->error("message");
        $logger->warning("message");
        $logger->notice("message");
        $logger->info("message");
        $logger->debug("message");
        $logger->log(12, "message");
        $this->assertTrue(true);
    }

    function testHandlerNoHandler() {
        $logger = (new Logger("loggerName"))->withHandler($this->getEmptyHandler(Logger::ERROR));
        $logger->warning("message");
        $this->assertTrue(true);
    }

    function testContextKey() {
        $logger = new Logger("loggerName", [$mKey = "mKey" => $mValue = "mValue"]);
        $this->assertEquals([
            true,
            $mValue,
            false,
            null
        ],[
            $logger->hasContextKey($mKey),
            $logger->getContextKey($mKey),
            $logger->hasContextKey("unknown"),
            $logger->getContextKey("unknown"),
        ]);
    }
}