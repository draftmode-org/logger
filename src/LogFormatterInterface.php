<?php

namespace Terrazza\Component\Logger;

interface LogFormatterInterface {
    /**
     * @param LogRecord $logRecord
     * @return mixed
     */
    public function format(LogRecord $logRecord);
}