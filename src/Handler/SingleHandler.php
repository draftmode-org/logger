<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\FormatterInterface;
use Terrazza\Component\Logger\HandlerInterface;
use Terrazza\Component\Logger\Record;
use Terrazza\Component\Logger\LogWriterInterface;

class SingleHandler implements HandlerInterface {
    private HandlerPattern $pattern;
    private LogWriterInterface $writer;
    private FormatterInterface $formatter;
    public function __construct(HandlerPattern $pattern, ChannelInterface $channel, $format) {
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