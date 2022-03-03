<?php

namespace Terrazza\Component\Logger;
use DateTime;

class Record {
    private \DateTime $logDate;
    private string $loggerName;
    private int $logLevel;
    private string $logMessage;
    private array $context;
    private int $memUsed;
    private int $memAllocated;

    /**
     * @param DateTime $logDate
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logMessage
     * @param int $memUsed
     * @param int $memAllocated
     * @param array|null $context
     */
    protected function __construct(DateTime $logDate,
                                   string $loggerName,
                                   int $logLevel,
                                   string $logMessage,
                                   int $memUsed,
                                   int $memAllocated,
                                   array $context=null)
    {
        $this->logDate                              = $logDate;
        $this->loggerName                           = $loggerName;
        $this->logLevel                             = $logLevel;
        $this->logMessage                           = $logMessage;
        $this->memUsed                           	= $memUsed;
        $this->memAllocated                         = $memAllocated;
        $this->context                              = $context ?? [];
    }

    /**
     * @param string $loggerName
     * @param int $logLevel
     * @param string $logMessage
     * @param array|null $context
     * @return Record
     */
    public static function createRecord(string $loggerName,
                                        int $logLevel,
                                        string $logMessage,
                                        array $context=null): Record {
        return new self(
            new DateTime(),
            $loggerName,
            $logLevel,
            $logMessage,
            round(memory_get_usage(false) / 1024),
            round(memory_get_usage(true) / 1024),
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
     * @return string
     */
    public function getNamespace() :string {
        return $this->getCallerNamespace();
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
     * @return string
     */
    private function getCallerNamespace() : string {
        $traces                                     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace				                        = $traces[2];
        $className			                        = $trace["class"];
        return join("\\", array_slice(explode("\\", $className), 0, -1));
    }

    /**
     * @return array
     */
    public function getToken() : array {
        $namespace                                  = $this->getCallerNamespace();
        return [
            'Date' 				                    => $this->getLogDate(),
            'Level' 			                    => $this->getLogLevel(),
            'LevelName'     	                    => $this->getLogLevelName(),
            'LoggerName' 		                    => $this->getLoggerName(),
            'Namespace'			                    => $namespace,
            'sNamespace'		                    => basename($namespace),
            'MemUsed'			                    => $this->getMemUsed(),
            'MemAllocated'		                    => $this->getMemAllocated(),
            'Message' 			                    => $this->getLogMessage(),
            'Context'			                    => $this->getContext(),
        ];
    }
}