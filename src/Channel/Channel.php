<?php
namespace Terrazza\Component\Logger\Channel;

use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\LogWriterInterface;

class Channel implements ChannelInterface {
    private string $name;
    private LogWriterInterface $writer;
    private LogRecordFormatterInterface $formatter;
    private ?LogHandlerFilterInterface $filter;
    public function __construct(string $name, LogWriterInterface $writer, LogRecordFormatterInterface $formatter, ?LogHandlerFilterInterface $filter=null) {
        $this->name 								= $name;
        $this->writer 								= $writer;
        $this->formatter 							= $formatter;
        $this->filter                               = $filter;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return LogRecordFormatterInterface
     */
    public function getFormatter() : LogRecordFormatterInterface {
        return $this->formatter;
    }

    /**
     * @return LogHandlerFilterInterface|null
     */
    public function getFilter() :?LogHandlerFilterInterface {
        return $this->filter;
    }

    /**
     * @return LogWriterInterface
     */
    public function getWriter() : LogWriterInterface {
        return $this->writer;
    }
}