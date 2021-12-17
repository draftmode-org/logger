<?php

namespace Terrazza\Component\Logger;

class Channel implements ChannelInterface {
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
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface {
        return $this->formatter;
    }

    /**
     * @return LogWriterInterface
     */
    public function getWriter() : LogWriterInterface {
        return $this->writer;
    }
}