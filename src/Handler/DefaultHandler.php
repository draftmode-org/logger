<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\HandlerInterface;
use Terrazza\Component\Logger\LogRecord;

class DefaultHandler implements HandlerInterface {
    private int $logLevel;
    private ChannelHandlerInterface $channel;
    public function __construct(int $logLevel, ChannelHandlerInterface $channel) {
        $this->logLevel 							= $logLevel;
        $this->channel 								= $channel;
    }

    /**
     * @param LogRecord $record
     * @return bool
     */
    public function isHandling(LogRecord $record) : bool {
        return $record->getLogLevel() >= $this->logLevel;
    }

    /**
     * @return int
     */
    public function getLogLevel() : int {
        return $this->logLevel;
    }

    /**
     * @param LogRecord $record
     */
    public function write(LogRecord $record) : void {
        $this->channel->write($record);
    }

    public function close(): void {}
}