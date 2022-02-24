<?php
namespace Terrazza\Component\Logger\Tests\_Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;

class ChannelLoggerTest extends TestCase {
    function setUp(): void {
        HandlerMock::setUp();
    }
    function tearDown(): void {
        HandlerMock::tearDown();
    }

    private function warningHandler() : array {
        return [
            Logger::WARNING,
            ["LoggerName", "Level", "Message"]
        ];
    }

    private function errorHandler() : array {
        return [
            Logger::ERROR,
            ["LoggerName", "LevelName", "Message"]
        ];
    }

    /**
     * test includes, calling error, and loglevel is warning
     */
    function testWithHandler() {
        $channelHandler = HandlerMock::getChannelHandler();
        $channelHandler->pushHandler(...$this->warningHandler());
        $logger = (new Logger($loggerName = "loggerName"))->withHandler($channelHandler);
        $logger->error($eMessage = "eMessage");
        $this->assertEquals(
            "$loggerName|".Logger::ERROR."|$eMessage",
            HandlerMock::getContent()
        );
    }

    /**
     * test includes
     * - 2 handler (warning, error) with different formats
     * - pushed in the right order (logLevel based)
     * ...calling error has to retrieve the error format
     */
    function testWithTwoHandler() {
        $channelHandler = HandlerMock::getChannelHandler();
        $channelHandler->pushHandler(...$this->errorHandler());
        $channelHandler->pushHandler(...$this->warningHandler());
        $logger = (new Logger($loggerName = "loggerName"))->withHandler($channelHandler);
        $logger->error($eMessage = "eMessage");
        $this->assertEquals(
            "$loggerName|".Logger::$levels[Logger::ERROR]."|$eMessage",
            HandlerMock::getContent()
        );
    }

    /**
     * test includes
     * - 2 handler (warning, error) with different formats
     * - pushed in the opposite order (logLevel based)
     * ...calling error has to retrieve the error format
     */
    function testWithTwoHandlerOppositeOrder() {
        $channelHandler = HandlerMock::getChannelHandler();
        $channelHandler->pushHandler(...$this->warningHandler());
        $channelHandler->pushHandler(...$this->errorHandler());
        $logger = (new Logger($loggerName = "loggerName"))->withHandler($channelHandler);
        $logger->error($eMessage = "eMessage");
        $this->assertEquals(
            "$loggerName|".Logger::$levels[Logger::ERROR]."|$eMessage",
            HandlerMock::getContent()
        );
    }
}