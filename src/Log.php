<?php
namespace Terrazza\Component\Logger;

class Log implements LogInterface {
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
    private array $context                          = [];
    /**
     * @var array|LogHandlerInterface[]
     */
    private array $handlers;
    private array $ignoreLogLevels                  = [];
    private array $ignoreLogLevelHandlers           = [];

    public function __construct(string $loggerName, LogHandlerInterface ...$handler) {
        $this->loggerName                           = $loggerName;
        $this->handlers                             = $handler ?? [];
    }

    /**
     * @param LogHandlerInterface $handler
     * @return LogInterface
     */
    public function withHandler(LogHandlerInterface $handler) : LogInterface {
        $logger                                     = clone $this;
        $logger->handlers[]                         = $handler;
        return $logger;
    }

    /**
     * @param string $namespace
     * @return LogInterface
     */
    public function withNamespace(string $namespace) : LogInterface {
        $logger                                     = clone $this;
        $logger->namespace                          = $namespace;
        return $logger;
    }

    /**
     * @param string $method
     * @return LogInterface
     */
    public function withMethod(string $method) : LogInterface {
        $logger                                     = clone $this;
        $logger->method                             = $method;
        return $logger;
    }

    /**
     * @param array $context
     * @return LogInterface
     */
    public function withContext(array $context) : LogInterface {
        $logger                                     = clone $this;
        $logger->context                            = $context;
        return $logger;
    }

    /**
     * @param string $contextKey
     * @param mixed $value
     */
    public function addContextValue(string $contextKey, $value) : void {
        $this->context[$contextKey]                 = $value;
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

    private function pushSkipLogLevelHandler(int $logLevel, LogHandlerInterface $handler) : void {
        if (!array_key_exists($logLevel, $this->ignoreLogLevelHandlers)) {
            $this->ignoreLogLevelHandlers[$logLevel] = [];
        }
        $handlerClassName                           = get_class($handler);
        if (!in_array($handlerClassName, $this->ignoreLogLevelHandlers[$logLevel])) {
            array_push($this->ignoreLogLevelHandlers[$logLevel], $handlerClassName);
        }
        if (count($this->ignoreLogLevelHandlers[$logLevel]) === count($this->handlers)) {
            array_push($this->ignoreLogLevels, $logLevel);
        }
    }

    private function skipLogLevel(int $logLevel) : bool {
        return (in_array($logLevel, $this->ignoreLogLevels));
    }

    /**
     * @param int $logLevel
     * @param $message
     * @param array $context
     */
    private function addMessage(int $logLevel, $message, array $context=[]) : void {
        //
        //
        //
        if ($this->skipLogLevel($logLevel)) return;
        //
        // push forward method + namespace into context
        //
        $context["method"]                          = $this->method;
        $context["namespace"]                       = $this->namespace;
        //
        //
        //
        if (count($this->context)) {
            $context                                = $this->context + $context;
        }
        //
        $logRecord                                  = LogRecord::createRecord(
            $this->loggerName,
            $logLevel,
            self::$levels[$logLevel],
            $message,
            $context);

        foreach ($this->handlers as $handler) {
            if ($handler->isHandling($logRecord)) {
                $handler->write($logRecord);
            } else {
                if (!$handler->hasLogPatterns()) {
                    $this->pushSkipLogLevelHandler($logLevel, $handler);
                }
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