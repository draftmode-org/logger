<?php

namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\IChannelHandler;
use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\Record;

class ChannelHandler implements IHandler, IChannelHandler {
    /** @var array|IHandler[] */
    private array $handler=[];
    private IChannel $channel;
    public function __construct(IChannel $channel) {
        $this->channel                              = $channel;
    }

    /**
     * @param int $logLevel
     * @param array $format
     */
    public function pushHandler(int $logLevel, array $format) : void {
        $this->handler[$logLevel]                   = new SingleHandler($logLevel, $this->channel, $format);
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