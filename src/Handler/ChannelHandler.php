<?php

namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\LogWriterInterface;
use Terrazza\Component\Logger\Record\LogRecord;
use Terrazza\Component\Logger\LogHandlerInterface;

class ChannelHandler implements ChannelHandlerInterface {
    private LogWriterInterface $writer;
    private LogRecordFormatterInterface $formatter;
    private ?LogHandlerFilterInterface $filter;
    /** @var LogHandlerInterface[] */
    private array $logHandler=[];
    public function __construct(LogWriterInterface $writer, LogRecordFormatterInterface $formatter, ?LogHandlerFilterInterface $filter, LogHandlerInterface ...$logHandler) {
        $this->writer                               = $writer;
        $this->formatter                            = $formatter;
        $this->filter                               = $filter;
        foreach ($logHandler as $singleLogHandler) {
            $this->pushLogHandler($singleLogHandler);
        }
    }

    /**
     * @return LogWriterInterface
     */
    public function getWriter(): LogWriterInterface {
        return $this->writer;
    }

    /**
     * @return LogRecordFormatterInterface
     */
    public function getFormatter(): LogRecordFormatterInterface {
        return $this->formatter;
    }

    /**
     * @return LogHandlerFilterInterface|null
     */
    public function getFilter(): ?LogHandlerFilterInterface {
        return $this->filter;
    }

    /**
     * @return LogHandlerInterface[]
     */
    public function getLogHandler() : array {
        return $this->logHandler;
    }

    /**
     * @param LogHandlerInterface $logHandler
     */
    public function pushLogHandler(LogHandlerInterface $logHandler) : void {
        $logLevel                                   = $logHandler->getLogLevel();
        $this->logHandler[$logLevel]                = $logHandler;
        $this->sortLogHandler();
    }

    private function sortLogHandler() : void {
        krsort($this->logHandler);
    }

    /**
     * @param LogRecord $record
     * @return LogHandlerInterface|null
     */
    public function getEffectedHandler(LogRecord $record) :?LogHandlerInterface {
        foreach ($this->logHandler as $handler) {
            if ($handler->isHandling($record)) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * @param LogHandlerInterface $handler
     * @param LogRecord $record
     */
    public function writeRecord(LogHandlerInterface $handler, LogRecord $record): void {
        $this->writer->write(
            $this->formatter->formatRecord($record, $handler->getFormat())
        );
    }
}