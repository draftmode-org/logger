<?php
namespace Terrazza\Component\Logger\Tests\_Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogHandlerFilter;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;

class LoggerMessageTest extends TestCase {
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
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::WARNING,
            ["LoggerName", "Level", "Message"]));
        $logger             = (new Logger($loggerName = "loggerName",null, $channelHandler));
        $logger->error($message = "message");
        $this->assertEquals(
            "$loggerName|".Logger::ERROR."|$message",
            HandlerMock::getContent()
        );
    }

    function testWithContext() {
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::WARNING,
            ["LoggerName", "Level", "Message", "Context.iContent", "Context.wContent"]));
        $logger             = (new Logger($loggerName = "loggerName",["iContent" => $iContent = "content"], $channelHandler));
        $logger->warning($message = "message", ["wContent" => $wContent = "content"]);
        $this->assertEquals(
            "$loggerName|".Logger::WARNING."|$message|$iContent|$wContent",
            HandlerMock::getContent()
        );
    }

    function testWithFilter() {
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler(
            Logger::WARNING,
            ["LoggerName", "Level", "Message", "Context.iContent", "Context.wContent"],
            new LogHandlerFilter(["unknownNamespace"])));
        $logger             = (new Logger($loggerName = "loggerName",["iContent" => $iContent = "content"], $channelHandler));
        $logger->warning("message");
        $this->assertNull(HandlerMock::getContent());
    }
}