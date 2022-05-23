<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonConverter;
use Terrazza\Component\Logger\Formatter\LogRecordFormatter;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LoggerInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;
use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\Tests\_Mocks\FormattedRecordConverterMock;
use Terrazza\Component\Logger\Tests\_Mocks\HandlerMock;
use Terrazza\Component\Logger\Writer\LogStreamFileWriter;

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

    function testRegisterChannelHandlerSuccessful() {
        $logHandler     = HandlerMock::getLogHandler(Logger::ERROR);
        $channelHandler = HandlerMock::getChannelHandler($logHandler->getFormat());
        $channelHandler->pushLogHandler($logHandler);
        $logger         = new Logger("loggerName", null);
        $logger->registerChannelHandler($channelHandler);
        $this->assertTrue(true);
    }


    function testGetters() {
        $logger     = new Logger("loggerName", [$mKey = "mKey" => $mValue = "mValue"]);
        $logger->registerFatalHandler();
        $logger->registerErrorHandler();
        $logger->registerExceptionHandler();
        $this->assertTrue(true);
    }

    public function testNamespace() {
        $formatter          = new LogRecordFormatter(new NonScalarJsonConverter(), ["Message" => "{LoggerName} {Level} {Message} {Trace.Namespace}"]);
        $channelHandler     = new ChannelHandler(new LogStreamFileWriter(new FormattedRecordConverterMock(), HandlerMock::stream), $formatter, null);
        $logHandler         = new LogHandler(Logger::DEBUG);
        $channelHandler->pushLogHandler($logHandler);
        $logger             = new Logger($loggerName = "NamespaceTest", null, $channelHandler);
        $logger->debug($message = "my message");
        $this->assertEquals("$loggerName 100 $message ".__NAMESPACE__, HandlerMock::getContent());
    }

    /** getHandlerErrorLogger */
    private function getHandlerErrorLogger(string $loggerName, int $logLevel) : LoggerInterface {
        $channelHandler     = HandlerMock::getChannelHandler(["{LoggerName} {Level} {Message}"]);
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
        $this->assertEquals("$loggerName ".Logger::EMERGENCY." $eMessage (#$eCode)",HandlerMock::getContent());
    }

    /** handleException */
    function testHandleException() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::ERROR);
        $logger->handleException($ex = new \RuntimeException($exMsg="message"));
        $this->assertEquals("$loggerName ".Logger::EMERGENCY." application exception: $exMsg",HandlerMock::getContent());
    }

    /** handleFatalError */
    function testHandleFatalError() {
        $logger             = $this->getHandlerErrorLogger($loggerName="lName", Logger::WARNING);
        @fopen('xxx');
        $logger->handleFatalError();
        $this->assertEquals("$loggerName ".Logger::WARNING." fopen() expects at least 2 parameters, 1 given",HandlerMock::getContent());
    }

    /** exceptionFileName */
    function testExceptionFile() {
        $logHandler         = new class (Logger::WARNING) implements LogHandlerInterface {
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
        };
        $channelHandler     = HandlerMock::getChannelHandler();
        $channelHandler->pushLogHandler($logHandler);

        $logger             = (new Logger("logger", null, $channelHandler));
        $logger->setExceptionFileName(HandlerMock::stream.".err");

        @unlink(HandlerMock::stream.".err");
        $logger->warning("wMessage");
        $this->assertNotNull(HandlerMock::getContent(HandlerMock::stream.".err"));
        @unlink(HandlerMock::stream.".err");
    }

}