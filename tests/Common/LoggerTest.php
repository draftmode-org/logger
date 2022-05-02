<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;

class LoggerTest extends TestCase {
    private function getEmptyHandler(int $logLevel) : LogHandlerInterface {
        return new LogHandler(
            $logLevel
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
        $logger = (new Logger("loggerName", null,
            HandlerMock::getChannelHandler($this->getEmptyHandler(Logger::ERROR))
        ));
        $logger->warning("message");
        $this->assertTrue(true);
    }

    function testHandlerNoHandlerWrite() {
        $logger = (new Logger("loggerName", null,
            HandlerMock::getChannelHandler($this->getEmptyHandler(Logger::ERROR))
        ));
        $logger->error("message");
        $this->assertTrue(true);
    }

    function testPushLogHandlerSuccessful() {
        $channelHandler = HandlerMock::getChannelHandler();
        $channelName    = $channelHandler->getChannel()->getName();
        $logger     = new Logger("loggerName", null, $channelHandler);
        $logger->pushLogHandler($channelName, HandlerMock::getLogHandler(Logger::ERROR));
        $this->assertTrue(true);
    }

    function testPushLogHandlerChannelMissingException() {
        $logger = new Logger("loggerName");
        $this->expectException(RuntimeException::class);
        $logger->pushLogHandler("channel", HandlerMock::getLogHandler(Logger::ERROR));
    }

    function testGetters() {
        $logger     = new Logger("loggerName", [$mKey = "mKey" => $mValue = "mValue"]);
        $logger->registerFatalHandler();
        $logger->registerErrorHandler();
        $logger->registerExceptionHandler();
        $this->assertEquals([
            true,
            $mValue,
            false,
            null,
        ],[
            $logger->hasContextKey($mKey),
            $logger->getContextByKey($mKey),
            $logger->hasContextKey("unknown"),
            $logger->getContextByKey("unknown"),
        ]);
    }

}