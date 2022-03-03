<?php
namespace Terrazza\Component\Logger\Tests\_Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LoggerFilter;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;

class SingleLoggerTest extends TestCase {
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
        $logger = (new Logger($loggerName = "loggerName"))->withHandler(HandlerMock::getSingleHandler(
            Logger::WARNING,
            ["LoggerName", "Level", "Message"]
        ));
        $logger->error($message = "message");
        $this->assertEquals(
            "$loggerName|".Logger::ERROR."|$message",
            HandlerMock::getContent()
        );
    }

    function testWithContext() {
        $logger = (new Logger($loggerName = "loggerName", ["iContent" => $iContent = "content"]))
            ->withHandler(HandlerMock::getSingleHandler(
            Logger::WARNING,
            ["LoggerName", "Level", "Message", "Context.iContent", "Context.wContent"]
        ));
        $logger->warning($message = "message", ["wContent" => $wContent = "content"]);
        $this->assertEquals(
            "$loggerName|".Logger::WARNING."|$message|$iContent|$wContent",
            HandlerMock::getContent()
        );
    }

    function testWithFilter() {
        $logger = (new Logger("loggerName"))
            ->withHandler(HandlerMock::getSingleHandler(
                Logger::WARNING,
                ["LoggerName", "Level", "Message", "Context.iContent", "Context.wContent"],
                new LoggerFilter(["unknownNamespace"])
            ));
        $logger->warning("message");
        $this->assertNull(HandlerMock::getContent());
    }
}