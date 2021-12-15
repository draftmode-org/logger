<?php

namespace Terrazza\Component\Logger;

class ChannelHandler implements ChannelHandlerInterface {
    private string $name;
    private LogWriterInterface $writer;
    private FormatterInterface $formatter;
    public function __construct(string $name, LogWriterInterface $writer, FormatterInterface $formatter) {
        $this->name 								= $name;
        $this->writer 								= $writer;
        $this->formatter 							= $formatter;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return LogWriterInterface
     */
    public function getWriter() : LogWriterInterface {
        return $this->writer;
    }

    /**
     * @param LogRecord $record
     */
    public function write(LogRecord $record) : void {
        $this->writer->write(
            $this->formatter->format($record).PHP_EOL
        );
    }
}