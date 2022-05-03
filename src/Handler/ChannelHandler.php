<?php

namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\LogHandlerInterface;

class ChannelHandler implements ChannelHandlerInterface {
    private ChannelInterface $channel;
    /** @var LogHandlerInterface[] */
    private array $logHandler=[];
    public function __construct(ChannelInterface $channel, LogHandlerInterface ...$logHandler) {
        $this->channel                              = $channel;
        foreach ($logHandler as $singleLogHandler) {
            $this->pushLogHandler($singleLogHandler);
        }
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel(): ChannelInterface {
        return $this->channel;
    }

    /**
     * @return LogHandlerInterface[]
     */
    public function getLogHandler() : array {
        return $this->logHandler;
    }

    /**
     * @param LogHandlerInterface $logHandler
     */
    public function pushLogHandler(LogHandlerInterface $logHandler) : void {
        $logLevel                                   = $logHandler->getLogLevel();
        $this->logHandler[$logLevel]                = $logHandler;
        $this->sortLogHandler();
    }

    private function sortLogHandler() : void {
        krsort($this->logHandler);
    }

    /**
     * @param LogRecord $record
     * @return LogHandlerInterface|null
     */
    public function getEffectedHandler(LogRecord $record) :?LogHandlerInterface {
        foreach ($this->logHandler as $handler) {
            if ($handler->isHandling($record)) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * @param LogHandlerInterface $handler
     * @return LogRecordFormatterInterface
     */
    private function getHandlerFormatter(LogHandlerInterface $handler) : LogRecordFormatterInterface {
        if ($format = $handler->getFormat()) {
            return $this->channel->getFormatter()->withFormat($format);
        } else {
            return $this->channel->getFormatter();
        }
    }

    /**
     * @param LogHandlerInterface $handler
     * @param LogRecord $record
     */
    public function writeRecord(LogHandlerInterface $handler, LogRecord $record): void {
        $this->channel->getWriter()->write(
            $this->getHandlerFormatter($handler)->formatRecord($record)
        );
    }
}