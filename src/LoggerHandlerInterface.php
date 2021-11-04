<?php
namespace Terrazza\Component\Logger;
use Terrazza\Component\Logger\Handler\StreamHandlerWriteException;

interface LoggerHandlerInterface {
    public function isHandling(LogRecord $logRecord) : bool;
    public function hasLogPatterns() : bool;
    /**
     * @param LogRecord $logRecord
     * @throws StreamHandlerWriteException
     */
    public function write(LogRecord $logRecord) : void;
}