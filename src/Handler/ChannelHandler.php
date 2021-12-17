<?php

namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\HandlerInterface;
use Terrazza\Component\Logger\Record;

class ChannelHandler implements HandlerInterface, ChannelHandlerInterface {
    /** @var array|HandlerInterface[] */
    private array $handler=[];
    private ChannelInterface $channel;
    public function __construct(ChannelInterface $channel) {
        $this->channel                              = $channel;
    }

    /**
     * @param HandlerPattern $pattern
     * @param $format
     */
    public function pushHandler(HandlerPattern $pattern, $format) : void {
        $hashKey                                    = $pattern->getLogLevel();
        $this->handler[$hashKey]                    = new SingleHandler($pattern, $this->channel, $format);
        krsort($this->handler);
    }
    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record): bool {
        foreach ($this->handler as $handler) {
            if ($handler->isHandling($record)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Record $record
     */
    public function writeRecord(Record $record): void {
        foreach ($this->handler as $handler) {
            if ($handler->isHandling($record)) {
                $handler->writeRecord($record);
                break;
            }
        }
    }

    public function close(): void {
        foreach ($this->handler as $handler) {
            $handler->close();
        }
    }
}