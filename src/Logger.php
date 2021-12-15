<?php
namespace Terrazza\Component\Logger;

class Logger implements LoggerInterface {
    public const LOG                                = 50;
    public const DEBUG                              = 100;
    public const INFO                               = 200;
    public const NOTICE                             = 250;
    public const WARNING                            = 300;
    public const ERROR                              = 400;
    public const CRITICAL                           = 500;
    public const ALERT                              = 550;
    public const EMERGENCY                          = 600;
    public const EXCEPTION                          = 650;

    public static array $levels = [
        self::LOG                                   => 'LOG',
        self::DEBUG                                 => 'DEBUG',
        self::INFO                                  => 'INFO',
        self::NOTICE                                => 'NOTICE',
        self::WARNING                               => 'WARNING',
        self::ERROR                                 => 'ERROR',
        self::CRITICAL                              => 'CRITICAL',
        self::ALERT                                 => 'ALERT',
        self::EMERGENCY                             => 'EMERGENCY',
        self::EXCEPTION                             => 'EXCEPTION',
    ];

    private string $loggerName;
    private ?string $method                         = null;
    private ?string $namespace                      = null;
    private array $context;
    /**
     * @var array|HandlerInterface[]
     */
    private array $handlers;

    public function __construct(string $loggerName, ?array $context=null, HandlerInterface ...$handler) {
        $this->loggerName                           = $loggerName;
        $this->handlers                             = $handler ?? [];
        $this->context                              = $context ?? [];
    }

    /**
     * @param HandlerInterface $handler
     * @return LoggerInterface
     */
    public function withHandler(HandlerInterface $handler) : LoggerInterface {
        $logger                                     = clone $this;
        $logger->handlers[]                         = $handler;
        return $logger;
    }

    /**
     * @param string $namespace
     * @return LoggerInterface
     */
    public function withNamespace(string $namespace) : LoggerInterface {
        $logger                                     = clone $this;
        $logger->namespace                          = $namespace;
        return $logger;
    }

    /**
     * @param string $method
     * @return LoggerInterface
     */
    public function withMethod(string $method) : LoggerInterface {
        $logger                                     = clone $this;
        $logger->method                             = $method;
        return $logger;
    }

    /**
     * @param array $context
     * @return LoggerInterface
     */
    public function withContext(array $context) : LoggerInterface {
        $logger                                     = clone $this;
        $logger->context                            = $context;
        return $logger;
    }

    /**
     * @param int $logLevel
     * @param $message
     * @param array $context
     */
    private function addMessage(int $logLevel, $message, array $context=[]) : void {
        //
        // context will be extended / overloaded by global context
        //
        if (count($this->context)) {
            $context                                = $context + $this->context;
        }
        //
        $record = LogRecord::createRecord(
            $this->loggerName,
            $logLevel,
            $message,
            $this->namespace,
            $this->method,
            $context ?? []
        );

        foreach ($this->handlers as $handler) {
            if ($handler->isHandling($record)) {
                $handler->write($record);
            }
        }
    }

    public function emergency($message, array $context = array(), int $line=null) : void {
        $this->addMessage(self::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array()) : void {
        $this->addMessage(self::ALERT, $message, $context);
    }

    public function critical($message, array $context = array()) : void  {
        $this->addMessage(self::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array()) : void  {
        $this->addMessage(self::ERROR,$message, $context);
    }

    public function warning($message, array $context = array()) : void  {
        $this->addMessage(self::WARNING, $message, $context);
    }

    public function notice($message, array $context = array()) : void  {
        $this->addMessage(self::NOTICE, $message, $context);
    }

    public function info($message, array $context = array()) : void  {
        $this->addMessage(self::INFO, $message, $context);
    }

    public function debug($message, array $context = array()) : void  {
        $this->addMessage(self::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array()) : void  {
        $this->addMessage(self::LOG, $message, $context);
    }
}