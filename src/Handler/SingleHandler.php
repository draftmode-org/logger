<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\IRecordFormatter;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\IWriter;

class SingleHandler implements IHandler {
    private HandlerPattern $pattern;
    private IWriter $writer;
    private IRecordFormatter $formatter;
    public function __construct(HandlerPattern $pattern, IChannel $channel, array $format) {
        $this->pattern 							    = $pattern;
        $this->writer                               = $channel->getWriter();
        $this->formatter 							= $channel->getFormatter()->withFormat($format);
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record) : bool {
        return $record->getLogLevel() >= $this->pattern->getLogLevel();
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