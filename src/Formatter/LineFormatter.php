<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\LogFormatterInterface;
use Terrazza\Component\Logger\LogRecord;

class LineFormatter implements LogFormatterInterface {
    private string $lineBreak;
    public function __construct() {
        $this->lineBreak                            = php_sapi_name() == "cli" ? PHP_EOL : "<br/>";
    }

    /**
     * @param LogRecord $logRecord
     * @return bool|mixed|string|null
     */
    public function format(LogRecord $logRecord) {
        $msg                                        = [];
        $msg[]                                      = "[".$logRecord->getLogDate()->format("Y-m-d H:i:s.u")."]";
        $msg[]                                      = "[".$logRecord->getLoggerName()."]";
        $msg[]                                      = "[".$logRecord->getLogLevelName()."]";
        if ($method = $logRecord->shiftContext("method")) {
            $msg[]                                  = "$method()";
        }
        if ($line = $logRecord->shiftContext("line")) {
            $msg[]                                  = "[line: $line]";
        }
        if (strlen($logRecord->getLogMessage())) {
            $msg[]                                  = $logRecord->getLogMessage();
        }
        $line                                       = print_r(join(" ", $msg).$this->lineBreak, true);
        if ($arguments = $logRecord->shiftContext("arguments")) {
            if ($next = $this->formatObject($arguments)) {
                $line                               .= $this->lineBreak . print_r($next, true) . $this->lineBreak;
            }
        }
        $context                                    = $logRecord->getContext();
        if ($context && count($context)) {
            if ($next = $this->formatObject($arguments)) {
                $line                               .= $this->lineBreak . print_r($next, true) . $this->lineBreak;
            }
        }
        return $line;
    }

    /**
     * @param mixed $object
     * @return string|null
     */
    private function formatObject($object) :?string {
        $print                                      = false;
        if (is_array($object)) {
            if (count($object)) {
                $print                              = true;
            }
        }
        if ($print) {
            return print_r($object, true);
        } else {
            return null;
        }
    }
}