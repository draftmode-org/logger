<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\ILoggerFilter;
use Terrazza\Component\Logger\IRecordFormatter;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\IWriter;

class SingleHandler implements IHandler {
    private int $logLevel;
    private IWriter $writer;
    private IRecordFormatter $formatter;
    private ?ILoggerFilter $filter;
    public function __construct(int $logLevel, IChannel $channel, ?array $format=null, ?ILoggerFilter $filter=null) {
        $this->logLevel 							= $logLevel;
        $this->writer                               = $channel->getWriter();
        $this->formatter 							= $format ? $channel->getFormatter()->withFormat($format) : $channel->getFormatter();
        $this->filter 							    = $filter ?? $channel->getFilter();
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record) : bool {
        /*
         * record filter
         */
        if ($record->getLogLevel() >= $this->logLevel) {
            /**
             * Handler-/ChannelFilter
             */
            if ($this->filter) {
                return $this->filter->isHandling($this->getCallerNamespace());
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Record $record
     */
    public function writeRecord(Record $record) : void {
        $this->writer->write(
            $this->formatter->formatRecord($record)
        );
    }

    public function close(): void {}

    /**
     * @return string
     */
    private function getCallerNamespace() : string {
        $traces                                     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace				                        = array_pop($traces);
        $className			                        = $trace["class"];
        return join("\\", array_slice(explode("\\", $className), 0, -1));
    }
}