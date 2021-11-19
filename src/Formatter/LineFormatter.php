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
            $line                                   .= json_encode(["arguments" => $arguments], JSON_PRETTY_PRINT).$this->lineBreak;
        }
        $context                                    = $logRecord->getContext();
        if ($context && count($context)) {
            $line                                   .= json_encode(["context" => $context], JSON_PRETTY_PRINT).$this->lineBreak;
        }
        return $line;
    }
}