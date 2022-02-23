<?php

namespace Terrazza\Component\Logger;

class Channel implements IChannel {
    private string $name;
    private IWriter $writer;
    private IRecordFormatter $formatter;
    public function __construct(string $name, IWriter $writer, IRecordFormatter $formatter) {
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
     * @return IRecordFormatter
     */
    public function getFormatter() : IRecordFormatter {
        return $this->formatter;
    }

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter {
        return $this->writer;
    }
}