<?php

namespace Terrazza\Component\Logger;

interface FormatterInterface {
    /**
     * @param LogRecord $logRecord
     * @return string
     */
    public function format(LogRecord $logRecord) : string;
}