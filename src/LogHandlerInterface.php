<?php
namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Record\LogRecord;

interface LogHandlerInterface {
    /**
     * @return int
     */
    public function getLogLevel(): int;

    /**
     * @return array|null
     */
    public function getFormat() :?array;

    /**
     * @return LogHandlerFilterInterface|null
     */
    public function getFilter() :?LogHandlerFilterInterface;

    /**
     * @param LogRecord $record
     * @return bool
     */
    public function isHandling(LogRecord $record) : bool;
}