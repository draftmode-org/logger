<?php

namespace Terrazza\Component\Logger;
use DateTime;

class LogRecord {
    private \DateTime $logDate;
    private string $loggerName;
    private int $logLevel;
    private string $logMessage;
    private array $context;
    private int $memUsed;
    private int $memAllocated;
    private ?string $namespace=null;
    private ?string $method=null;

    /**
     * @param DateTime $logDate
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logMessage
     * @param int $memUsed
     * @param int $memAllocated
     * @param string|null $namespace
     * @param string|null $method
     * @param array|null $context
     */
    protected function __construct(DateTime $logDate,
                                   string $loggerName,
                                   int $logLevel,
                                   string $logMessage,
                                   int $memUsed,
                                   int $memAllocated,
                                   string $namespace=null,
                                   string $method=null,
                                   array $context=null)
    {
        $this->logDate                              = $logDate;
        $this->loggerName                           = $loggerName;
        $this->logLevel                             = $logLevel;
        $this->logMessage                           = $logMessage;
        $this->memUsed                           	= $memUsed;
        $this->memAllocated                         = $memAllocated;
        $this->namespace                        	= $namespace;
        $this->method                         		= $method;
        $this->context                              = $context ?? [];
    }

    /**
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logMessage
     * @param string|null $namespace
     * @param string|null $method
     * @param array|null $context
     * @return LogRecord
     */
    public static function createRecord(string $loggerName,
                                        int $logLevel,
                                        string $logMessage,
                                        ?string $namespace=null,
                                        ?string $method=null,
                                        array $context=null): LogRecord {
        return new self(
            new DateTime(),
            $loggerName,
            $logLevel,
            $logMessage,
            round(memory_get_usage(false) / 1024),
            round(memory_get_usage(true) / 1024),
            $namespace,
            $method,
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
        return Logger::$levels[$this->logLevel];
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
    public function getNamespace() :?string {
        return $this->namespace;
    }

    /**
     * @return string|null
     */
    public function getMethod() :?string {
        return $this->method;
    }

    /**
     * @return int
     */
    public function getMemUsed() : int {
        return $this->memUsed;
    }

    /**
     * @return int
     */
    public function getMemAllocated() : int {
        return $this->memAllocated;
    }

    /**
     * @return array
     */
    public function getContext() : array {
        return $this->context;
    }

    /**
     * @param string $dateFormat
     * @return array
     */
    public function getToken(string $dateFormat="Y-m-d H:i:s.u") : array {
        return [
            'Date' 				=> $this->getLogDate()->format($dateFormat),
            'Level' 			=> $this->getLogLevel(),
            'LevelName'     	=> $this->getLogLevelName(),
            'LoggerName' 		=> $this->getLoggerName(),
            'Namespace'			=> $this->getNamespace(),
            'sNamespace'		=> $this->getNamespace() ? basename($this->getNamespace()) : null,
            'Method'			=> $this->getMethod(),
            'sMethod'			=> $this->getMethod() ? basename($this->getMethod()) : null,
            'MemUsed'			=> $this->getMemUsed(),
            'MemAllocated'		=> $this->getMemAllocated(),
            'Message' 			=> $this->getLogMessage(),
            'Context'			=> $this->getContext(),
        ];
    }
}