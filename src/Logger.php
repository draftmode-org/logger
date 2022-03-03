<?php
namespace Terrazza\Component\Logger;

class Logger implements ILogger {
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
    private array $context;
    /**
     * @var array|IHandler[]
     */
    private array $handler;

    public function __construct(string $loggerName, ?array $context=null, IHandler ...$handler) {
        $this->loggerName                           = $loggerName;
        $this->handler                              = $handler ?? [];
        $this->context                              = $context ?? [];
    }

    /**
     * @param IHandler $handler
     * @return ILogger
     */
    public function withHandler(IHandler $handler) : ILogger {
        $logger                                     = clone $this;
        $logger->handler[]                          = $handler;
        return $logger;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasContextKey(string $key) : bool {
        $token                                      = $this->context;
        foreach (explode(".", $key) as $tokenKey) {
            if (array_key_exists($tokenKey, $token)) {
                $token								= $token[$tokenKey];
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getContextByKey(string $key) {
        $token                                      = $this->context;
        foreach (explode(".", $key) as $tokenKey) {
            if (array_key_exists($tokenKey, $token)) {
                $token								= $token[$tokenKey];
            } else {
                return null;
            }
        }
        return $token;
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
        $record = Record::createRecord(
            $this->loggerName,
            $logLevel,
            $message,
            $context ?? []
        );

        foreach ($this->handler as $handler) {
            if ($handler->isHandling($record)) {
                $handler->writeRecord($record);
            }
        }
    }

    public function emergency($message, array $context = array()) : void {
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