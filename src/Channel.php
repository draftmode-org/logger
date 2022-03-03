<?php

namespace Terrazza\Component\Logger;

class Channel implements IChannel {
    private string $name;
    private IWriter $writer;
    private IRecordFormatter $formatter;
    private ?LoggerFilter $filter;
    public function __construct(string $name, IWriter $writer, IRecordFormatter $formatter, ?LoggerFilter $filter=null) {
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
     * @return IRecordFormatter
     */
    public function getFormatter() : IRecordFormatter {
        return $this->formatter;
    }

    /**
     * @return LoggerFilter|null
     */
    public function getFilter() :?LoggerFilter {
        return $this->filter;
    }

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter {
        return $this->writer;
    }
}