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

    private ?string $traceNamespace=null;
    private ?string $traceMethod=null;
    private ?int $traceLine=null;

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
     * @param int $nbrTrace
     * @return void
     */
    private function getCallerNamespace(int $nbrTrace) : void {
        $traces                                     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $traceClass			                        = $traces[$nbrTrace+1];
        $traceLine                                  = $traces[$nbrTrace];
        $className                                  = $traceClass["class"];
        $this->traceMethod                          = $traceClass["function"];
        $this->traceLine                            = $traceLine["line"];
        $namespace                                  = join("\\", array_slice(explode("\\", $className), 0, -1));
        $this->traceNamespace                       = $namespace;
    }

    /**
     * @return array
     */
    public function getToken() : array {
        $this->getCallerNamespace(5);
        return [
            'Date' 				                    => $this->getLogDate(),
            'Level' 			                    => $this->getLogLevel(),
            'LevelName'     	                    => $this->getLogLevelName(),
            'LoggerName' 		                    => $this->getLoggerName(),
            'Namespace'			                    => $this->traceNamespace,
            'sNamespace'		                    => $this->traceNamespace ? basename($this->traceNamespace) : null,
            'Method'			                    => $this->traceMethod,
            'Line'			                        => $this->traceLine,
            'MemUsed'			                    => $this->getMemUsed(),
            'MemAllocated'		                    => $this->getMemAllocated(),
            'Message' 			                    => $this->getLogMessage(),
            'Context'			                    => $this->getContext(),
        ];
    }
}