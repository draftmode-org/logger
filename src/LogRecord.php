<?php

namespace Terrazza\Component\Logger;
use DateTime;

class LogRecord {
    private DateTime $logDate;
    private string $loggerName;
    private int $logLevel;
    private string $logLevelName;
    private string $logMessage;
    private array $context;

    /**
     * @param DateTime $logDate
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logLevelName
     * @param string $logMessage
     * @param array|null $context
     */
    protected function __construct(DateTime $logDate,
                                   string $loggerName,
                                   int $logLevel,
                                   string $logLevelName,
                                   string $logMessage,
                                   array $context=null)
    {
        $this->logDate                              = $logDate;
        $this->loggerName                           = $loggerName;
        $this->logLevel                             = $logLevel;
        $this->logLevelName                         = $logLevelName;
        $this->logMessage                           = $logMessage;
        $this->context                              = $context ?? [];
    }

    /**
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logLevelName
     * @param string $logMessage
     * @param array|null $context
     * @return LogRecord
     */
    public static function createRecord(string $loggerName,
                                        int $logLevel,
                                        string $logLevelName,
                                        string $logMessage,
                                        array $context=null): LogRecord {
        return new self(
            new DateTime(),
            $loggerName,
            $logLevel,
            $logLevelName,
            $logMessage,
            $context
        );
    }

    /**
     * @return DateTime
     */
    public function getLogDate(): DateTime
    {
        return $this->logDate;
    }

    /**
     * @return string
     */
    public function getLoggerName(): string
    {
        return $this->loggerName;
    }

    /**
     * @return int
     */
    public function getLogLevel(): int
    {
        return $this->logLevel;
    }

    /**
     * @return string
     */
    public function getLogLevelName(): string
    {
        return $this->logLevelName;
    }

    /**
     * @return string
     */
    public function getLogMessage(): string
    {
        return $this->logMessage;
    }

    /**
     * @return array
     */
    public function getContext() : array {
        return $this->context;
    }

    /**
     * @param string $contextKey
     * @return mixed|null
     */
    public function shiftContext(string $contextKey) {
        if ($this->hasContextKey($contextKey)) {
            $context                                = $this->context[$contextKey];
            unset($this->context[$contextKey]);
            return $context;
        } else {
            return null;
        }
    }

    /**
     * @param string $contextKey
     * @return mixed|null
     */
    public function getContextValue(string $contextKey) {
        return $this->context[$contextKey] ?? null;
    }

    /**
     * @param string $contextKey
     * @return bool
     */
    public function hasContextKey(string $contextKey) : bool {
        return array_key_exists($contextKey, $this->context);
    }
}