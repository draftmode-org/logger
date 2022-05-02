<?php

namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\ChannelInterface;
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
        $logHandler                                 = $logHandler->setFormatter($this->channel->getFormatter());
        $this->logHandler[$logLevel]                = $logHandler;
        $this->sortLogHandler();
    }

    private function sortLogHandler() : void {
        krsort($this->logHandler);
    }

    /**
     * @param LogRecord $record
     */
    public function handleRecord(LogRecord $record): void {
        foreach ($this->logHandler as $handler) {
            if ($handler->isHandling($record)) {
                $this->channel->getWriter()->write(
                    $handler->getFormatter()->formatRecord($record)
                );
                break;
            }
        }
    }
}