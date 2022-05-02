<?php
namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Record\LogRecord;

interface ChannelHandlerInterface {
    /**
     * @return ChannelInterface
     */
    public function getChannel() : ChannelInterface;

    /**
     * @return LogHandlerInterface[]
     */
    public function getLogHandler() : array;

    /**
     * @param LogHandlerInterface $logHandler
     */
    public function pushLogHandler(LogHandlerInterface $logHandler) : void;

    /**
     * @param LogRecord $record
     */
    public function handleRecord(LogRecord $record): void;
}