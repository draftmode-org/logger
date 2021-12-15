<?php

namespace Terrazza\Component\Logger;

interface FormatterInterface {
    /**
     * @param LogRecord $logRecord
     * @return mixed
     */
    public function format(LogRecord $logRecord);
}