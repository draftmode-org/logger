<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\LoggerFormatterInterface;
use Terrazza\Component\Logger\LoggerHandlerInterface;
use Terrazza\Component\Logger\LogRecord;

class StreamHandler implements LoggerHandlerInterface {
    private int $logLevel;
    private LoggerFormatterInterface $formatter;
    private string $stream;
    public function __construct(int $logLevel,
                                LoggerFormatterInterface $formatter,
                                string $stream
    ) {
        $this->logLevel                             = $logLevel;
        $this->formatter                            = $formatter;
        $this->stream                               = $stream;
    }

    public function isHandling(LogRecord $logRecord): bool {
        return $logRecord->getLogLevel() >= $this->logLevel;
    }

    public function hasLogPatterns(): bool {
        return false;
    }

    /**
     * @param LogRecord $logRecord
     * @throws StreamHandlerWriteException
     */
    public function write(LogRecord $logRecord) : void {
        $message                                    = $this->formatter->format($logRecord);
        if (@file_put_contents($this->stream, $message) === false) {
            throw new StreamHandlerWriteException($this->stream);
        }
    }
}