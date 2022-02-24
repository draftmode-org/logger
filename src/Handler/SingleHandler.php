<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\IRecordFormatter;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\IWriter;

class SingleHandler implements IHandler {
    private int $logLevel;
    private IWriter $writer;
    private IRecordFormatter $formatter;
    public function __construct(int $logLevel, IChannel $channel, ?array $format=null) {
        $this->logLevel 							= $logLevel;
        $this->writer                               = $channel->getWriter();
        $this->formatter 							= $format ? $channel->getFormatter()->withFormat($format) : $channel->getFormatter();
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record) : bool {
        return $record->getLogLevel() >= $this->logLevel;
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
}