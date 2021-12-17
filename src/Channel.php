<?php

namespace Terrazza\Component\Logger;

class Channel implements IChannel {
    private string $name;
    private IWriter $writer;
    private IFormatter $formatter;
    public function __construct(string $name, IWriter $writer, IFormatter $formatter) {
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
     * @return IFormatter
     */
    public function getFormatter() : IFormatter {
        return $this->formatter;
    }

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter {
        return $this->writer;
    }
}