<?php

namespace Terrazza\Component\Logger;

interface LoggerFormatterInterface {
    /**
     * @param LogRecord $logRecord
     * @return mixed
     */
    public function format(LogRecord $logRecord);
}