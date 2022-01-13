<?php
namespace Terrazza\Component\Logger\Tests\_Examples;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Logger;
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
            new HandlerPattern(Logger::WARNING),
            ["LoggerName", "Level", "Message"]
        ));
        $logger->error($message = "message");
        $this->assertEquals(
            "$loggerName|".Logger::ERROR."|$message",
            HandlerMock::getContent()
        );
    }

    function testWithNamespace() {
        $logger = (new Logger($loggerName = "loggerName"))->withHandler(HandlerMock::getSingleHandler(
            new HandlerPattern(Logger::WARNING),
            ["LoggerName", "Level", "Message", "Namespace", "sNamespace"]
        ));
        $logger = $logger->withNamespace(__NAMESPACE__);
        $logger->warning($message = "message");
        $this->assertEquals(
            "$loggerName|".Logger::WARNING."|$message|".__NAMESPACE__."|".basename(__NAMESPACE__),
            HandlerMock::getContent()
        );
    }

    function testWithMethod() {
        $logger = (new Logger($loggerName = "loggerName"))->withHandler(HandlerMock::getSingleHandler(
            new HandlerPattern(Logger::WARNING),
            ["LoggerName", "Level", "Message", "Method", "sMethod", "context.index"]
        ));
        $logger = $logger->withMethod(__METHOD__);
        $logger->warning($message = "message");
        $this->assertEquals(
            "$loggerName|".Logger::WARNING."|$message|".__METHOD__."|".basename(__METHOD__),
            HandlerMock::getContent()
        );
    }

    function testWithContext() {
        $logger = (new Logger($loggerName = "loggerName", ["iContent" => $iContent = "content"]))->withHandler(HandlerMock::getSingleHandler(
            new HandlerPattern(Logger::WARNING),
            ["LoggerName", "Level", "Message", "Context.iContent", "Context.wContent"]
        ));
        $logger = $logger->withMethod(__METHOD__);
        $logger->warning($message = "message", ["wContent" => $wContent = "content"]);
        $this->assertEquals(
            "$loggerName|".Logger::WARNING."|$message|$iContent|$wContent",
            HandlerMock::getContent()
        );
    }
}