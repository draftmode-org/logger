<?php
namespace Terrazza\Component\Logger\Tests\_Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;

class LoggerTest extends TestCase {
    function setUp(): void {
        HandlerMock::setUp();
    }
    function tearDown(): void {
        HandlerMock::tearDown();
    }

    /**
     * test includes, calling error, and loglevel is warning
     */
    function testWithHandler() {
        $channelHandler     = HandlerMock::getChannelHandler(["LoggerName", "Level", "Message"]);
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::ERROR));
        $logger             = (new Logger($loggerName = "loggerName", null, $channelHandler));
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
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::WARNING,
            ["LoggerName", "Message"]));
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::ERROR,
            ["LoggerName", "LevelName", "Message"]));
        $logger             = (new Logger($loggerName = "loggerName", null, $channelHandler));
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
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::ERROR,
            ["LoggerName", "LevelName", "Message"]));
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::WARNING,
            ["LoggerName", "Message"]));
        $logger             = (new Logger($loggerName = "loggerName", null, $channelHandler));
        $logger->error($eMessage = "eMessage");
        $this->assertEquals(
            "$loggerName|".Logger::$levels[Logger::ERROR]."|$eMessage",
            HandlerMock::getContent()
        );
    }
}