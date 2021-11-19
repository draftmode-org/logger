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
    /**
     * @var array|LogHandlerInterface[]
     */
    private array $handlers;
    private ?array $initContext                     = null;
    private array $ignoreLogLevels                  = [];
    private array $ignoreLogLevelHandlers           = [];

    public function __construct(string $loggerName, LogHandlerInterface ...$handler) {
        $this->loggerName                           = $loggerName;
        $this->handlers                             = $handler ?? [];
    }

    public function withHandler(LogHandlerInterface $handler) : LogInterface {
        $logger                                     = clone $this;
        $logger->handlers[]                         = $handler;
        return $logger;
    }

    public function withMethod(string $method) : LogInterface {
        $logger                                     = clone $this;
        $logger->method                             = $method;
        return $logger;
    }

    public function withInitContext(array $context) : LogInterface {
        $logger                                     = clone $this;
        $logger->initContext                        = $context;
        return $logger;
    }

    private function pushIgnoreLogLevelHandler(int $logLevel, LogHandlerInterface $handler) : void {
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

    private function ignoreLogLevelHandler(int $logLevel) : bool {
        return (in_array($logLevel, $this->ignoreLogLevels));
    }

    /**
     * @param int $logLevel
     * @param $message
     * @param array $context
     * @param int|null $line
     */
    private function addMessage(int $logLevel, $message, array $context=[], int $line=null) : void {
        //
        if ($this->ignoreLogLevelHandler($logLevel)) return;
        //
        $context["method"]                          = $this->method;
        $context["line"]                            = $line;
        $logRecord                                  = LogRecord::createRecord(
            $this->loggerName,
            $logLevel,
            self::$levels[$logLevel],
            $message,
            $context,
            $this->initContext);

        foreach ($this->handlers as $handler) {
            if ($handler->isHandling($logRecord)) {
                $handler->write($logRecord);
            } else {
                if (!$handler->hasLogPatterns()) {
                    $this->pushIgnoreLogLevelHandler($logLevel, $handler);
                }
            }
        }
    }

    public function emergency($message, array $context = array(), int $line=null) : void {
        $this->addMessage(self::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array(), int $line=null) : void {
        $this->addMessage(self::ALERT, $message, $context);
    }

    public function critical($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::ERROR,$message, $context);
    }

    public function warning($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::WARNING, $message, $context);
    }

    public function notice($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::NOTICE, $message, $context);
    }

    public function info($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::INFO, $message, $context);
    }

    public function debug($message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::DEBUG, $message, $context, $line);
    }

    public function log($level, $message, array $context = array(), int $line=null) : void  {
        $this->addMessage(self::LOG, $message, $context);
    }
}