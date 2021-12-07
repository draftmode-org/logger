<?php
namespace Terrazza\Component\Logger;
use Terrazza\Component\Logger\Handler\StreamHandlerWriteException;

interface LogHandlerInterface {
    /**
     * @param LogFormatterInterface $formatter
     * @return LogHandlerInterface
     */
    public function withFormatter(LogFormatterInterface $formatter) : LogHandlerInterface;

    /**
     * @return bool
     */
    public function hasFormatter() : bool;

    /**
     * @return LogFormatterInterface
     */
    public function getFormatter() : LogFormatterInterface;

    /**
     * @param LogRecord $logRecord
     * @return bool
     */
    public function isHandling(LogRecord $logRecord) : bool;

    /**
     * @return bool
     */
    public function hasLogPatterns() : bool;

    /**
     * @param LogRecord $logRecord
     * @throws StreamHandlerWriteException
     */
    public function write(LogRecord $logRecord) : void;
}