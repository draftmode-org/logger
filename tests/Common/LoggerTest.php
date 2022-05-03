<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LoggerInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;
use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\Record\LogRecord;
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
        $logger         = (new Logger("loggerName", null));
        $logger->warning("message");
        $this->assertTrue(true);
    }

    function testChannelHandler() {
        $logHandler     = HandlerMock::getLogHandler(Logger::ERROR);
        $channelHandler = HandlerMock::getChannelHandler($logHandler->getFormat());
        $channelName    = $channelHandler->getChannel()->getName();
        $logger         = new Logger("loggerName", null, $channelHandler);
        $this->assertEquals([
            null,
            $channelHandler,
        ],[
            $logger->getChannelHandler("unknown"),
            $logger->getChannelHandler($channelHandler->getChannel()->getName())
        ]);
    }

    function testPushLogHandlerSuccessful() {
        $logHandler     = HandlerMock::getLogHandler(Logger::ERROR);
        $channelHandler = HandlerMock::getChannelHandler($logHandler->getFormat());
        $channelName    = $channelHandler->getChannel()->getName();
        $logger         = new Logger("loggerName", null, $channelHandler);
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
        $this->assertTrue(true);
/*        $this->assertEquals([
            true,
            $mValue,
            false,
            null,
        ],[
            $logger->hasContextKey($mKey),
            $logger->getContextByKey($mKey),
            $logger->hasContextKey("unknown"),
            $logger->getContextByKey("unknown"),
        ]);*/
    }


    /** getHandlerErrorLogger */
    private function getHandlerErrorLogger(string $loggerName, int $logLevel) : LoggerInterface {
        $channelHandler     = HandlerMock::getChannelHandler(["LoggerName", "Level", "Message"]);
        $channelHandler->pushLogHandler(HandlerMock::getLogHandler($logLevel));
        return (new Logger($loggerName, null, $channelHandler));
    }
    function testHandleErrorAsNotice() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::ERROR);
        $logger->handleError(E_USER_NOTICE, "eMessage");
        $this->assertNull(HandlerMock::getContent());
    }
    function testHandleErrorAsWarning() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::ERROR);
        $logger->handleError(E_USER_WARNING, "eMessage");
        $this->assertNull(HandlerMock::getContent());
    }
    function testHandleErrorAsEmergency() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::ERROR);
        $logger->handleError($eCode = 10000, $eMessage = "eMessage");
        $this->assertEquals("$loggerName|".Logger::EMERGENCY."|$eMessage (#$eCode)",HandlerMock::getContent());
    }

    /** handleException */
    function testHandleException() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::ERROR);
        $logger->handleException($ex = new \RuntimeException($exMsg="message"));
        $this->assertEquals("$loggerName|".Logger::EMERGENCY."|application exception: $exMsg",HandlerMock::getContent());
    }

    /** handleFatalError */
    function testHandleFatalError() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::WARNING);
        @fopen('xxx');
        $logger->handleFatalError();
        $this->assertEquals("$loggerName|".Logger::WARNING."|fopen() expects at least 2 parameters, 1 given",HandlerMock::getContent());
    }

    /** exceptionFileName */
    function testExceptionFile() {
        $logger             = $this->getHandlerErrorLogger("lName", Logger::ERROR);
        $logger->setExceptionFileName(HandlerMock::stream.".err");
        if ($channel = $logger->getChannelHandler("channel")) {
            $channel->pushLogHandler(
                new class (Logger::WARNING) implements LogHandlerInterface {
                    private int $logLevel;
                    public function __construct(int $logLevel) {
                        $this->logLevel = $logLevel;
                    }
                    public function getLogLevel(): int { return $this->logLevel;}
                    public function getFormat(): ?array { return null;}
                    public function getFilter(): ?LogHandlerFilterInterface {return null;}
                    public function isHandling(LogRecord $record): bool {
                        $failure = 12 / 0;
                        return (bool)$failure;
                    }
                }
            );
        }
        @unlink(HandlerMock::stream.".err");
        $logger->warning("wMessage");
        $this->assertNotNull(HandlerMock::getContent(HandlerMock::stream.".err"));
        @unlink(HandlerMock::stream.".err");
    }

}