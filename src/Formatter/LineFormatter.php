<?php
namespace Terrazza\Component\Logger\Formatter;

use Terrazza\Component\Logger\LoggerFormatterInterface;
use Terrazza\Component\Logger\LogRecord;

class LineFormatter implements LoggerFormatterInterface {
    private string $lineBreak;
    public function __construct(string $lineBreak) {
        $this->lineBreak                            = $lineBreak ?? (php_sapi_name() == "cli" ? PHP_EOL : "<br/>");
    }

    /**
     * @param LogRecord $logRecord
     * @return bool|mixed|string|null
     */
    public function format(LogRecord $logRecord) {
        $msg                                        = [];
        $context                                    = $logRecord->getContext() ?? [];
        if (array_key_exists("method", $context)) {
            $msg[]                                  = $context["method"]."()";
            unset($context["method"]);
        }
        $msg[]                                      = $logRecord->getLogLevel();
        if (array_key_exists("line", $context)) {
            $msg[]                                  = "[line: ".$context["line"]."]";
            unset($context["line"]);
        }
        if (strlen($logRecord->getLogMessage())) {
            $msg[]                                  = $logRecord->getLogMessage();
        }
        $line                                       = print_r(join(" ", $msg).$this->lineBreak, true);
        if ($context && array_key_exists("arguments", $context)) {
            $arguments                              = $context["arguments"];
            if ($line = $this->formatObject($arguments)) {
                $line                               .= $this->lineBreak . print_r($arguments, true) . $this->lineBreak;
            }
            unset($context["arguments"]);
        }
        if ($context && count($context)) {
            if ($line = $this->formatObject($arguments)) {
                $line                               .= $this->lineBreak . print_r($context, true) . $this->lineBreak;
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