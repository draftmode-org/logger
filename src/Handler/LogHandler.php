<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;

class LogHandler implements LogHandlerInterface {
    private int $logLevel;
    private ?array $format;
    private ?LogHandlerFilterInterface $filter;
    private ?LogRecordFormatterInterface $formatter=null;
    public function __construct(int $logLevel, ?array $format=null, ?LogHandlerFilterInterface $filter=null) {
        $this->logLevel 							= $logLevel;
        $this->format                               = $format;
        $this->filter                               = $filter;
    }

    /**
     * @return int
     */
    public function getLogLevel(): int {
        return $this->logLevel;
    }

    /**
     * @return array|null
     */
    public function getFormat() :?array {
        return $this->format;
    }

    /**
     * @return LogHandlerFilterInterface|null
     */
    public function getFilter() :?LogHandlerFilterInterface {
        return $this->filter;
    }

    /**
     * @param LogRecordFormatterInterface $formatter
     * @return LogHandlerInterface
     */
    public function setFormatter(LogRecordFormatterInterface $formatter) : LogHandlerInterface {
        $logHandler                                 = clone $this;
        $logHandler->formatter                      = $this->format ? $formatter->withFormat($this->format) : $formatter;
        return $logHandler;
    }

    /**
     * @return LogRecordFormatterInterface
     */
    public function getFormatter() : LogRecordFormatterInterface {
        return $this->formatter;
    }

    /**
     * @param LogRecord $record
     * @return bool
     */
    public function isHandling(LogRecord $record) : bool {
        /*
         * record filter
         */
        if ($record->getLogLevel() >= $this->logLevel) {
            /**
             * Handler-/ChannelFilter
             */
            if ($this->filter) {
                return $this->filter->isHandling($record->getNamespace());
            } else {
                return true;
            }
        }
        return false;
    }
}