<?php

namespace Terrazza\Component\Logger;

use DateTime;

class LogRecord {
    private DateTime $logDate;
    private string $loggerName;
    private int $logLevel;
    private string $logLevelName;
    private string $logMessage;
    private ?string $logMethod;
    private ?array $context;
    private ?array $initContext;

    /**
     * @param DateTime $logDate
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logLevelName
     * @param string $logMessage
     * @param string|null $logMethod
     * @param array|null $context
     * @param array|null $initContext
     */
    protected function __construct(DateTime $logDate,
                                   string $loggerName,
                                   int $logLevel,
                                   string $logLevelName,
                                   string $logMessage,
                                   ?string $logMethod=null,
                                   ?array $context=null,
                                   ?array $initContext=null)
    {
        $this->logDate                              = $logDate;
        $this->loggerName                           = $loggerName;
        $this->logLevel                             = $logLevel;
        $this->logLevelName                         = $logLevelName;
        $this->logMessage                           = $logMessage;
        $this->logMethod                            = $logMethod;
        $this->context                              = $context;
        $this->initContext                          = $initContext;
    }

    public static function createRecord(string $loggerName,
                                        int $logLevel,
                                        int $logLevelName,
                                        string $logMessage,
                                        ?string $logMethod=null,
                                        ?array $context=null,
                                        ?array $initContext=null): LogRecord {
        return new self(
            new DateTime(),
            $loggerName,
            $logLevel,
            $logLevelName,
            $logMethod,
            $logMessage,
            $context,
            $initContext
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
     * @return string|null
     */
    public function getLogMethod():?string
    {
        return $this->logMethod;
    }

    /**
     * @return array|null
     */
    public function getContext():?array
    {
        return $this->context;
    }

    /**
     * @return array|null
     */
    public function getInitContext(): ?array
    {
        return $this->initContext;
    }
}