<?php
namespace Terrazza\Component\Logger;
use Terrazza\Component\Logger\Handler\StreamHandlerWriteException;

interface HandlerInterface {
    /**
     * @return int
     */
    public function getLogLevel() : int;

    /**
     * @param LogRecord $logRecord
     * @return bool
     */
    public function isHandling(LogRecord $logRecord) : bool;

    /**
     * @param LogRecord $logRecord
     * @throws StreamHandlerWriteException
     */
    public function write(LogRecord $logRecord) : void;

    public function close() : void;
}