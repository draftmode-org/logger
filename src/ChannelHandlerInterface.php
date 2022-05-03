<?php
namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Record\LogRecord;

interface ChannelHandlerInterface {
    /**
     * @return ChannelInterface
     */
    public function getChannel() : ChannelInterface;

    /**
     * @param LogHandlerInterface $logHandler
     */
    public function pushLogHandler(LogHandlerInterface $logHandler) : void;

    /**
     * @param LogRecord $record
     * @return LogHandlerInterface|null
     */
    public function getEffectedHandler(LogRecord $record) :?LogHandlerInterface;

    /**
     * @param LogHandlerInterface $handler
     * @param LogRecord $record
     */
    public function writeRecord(LogHandlerInterface $handler, LogRecord $record): void;
}