<?php
namespace Terrazza\Component\Logger;

class Log implements LogInterface {
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
     * @param string $logLevelName
     * @param $message
     * @param array $context
     */
    private function addMessage(int $logLevel, string $logLevelName, $message, array $context=[]) : void {
        //
        if ($this->ignoreLogLevelHandler($logLevel)) return;
        //
        $context["method"]                          = $this->method;
        $logRecord                                  = LogRecord::createRecord(
            $this->loggerName,
            $logLevel,
            $logLevelName,
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

    public function emergency($message, array $context = array()) {
        $this->addMessage(LOG_EMERG, "emergency", $message, $context);
    }

    public function alert($message, array $context = array()){
        $this->addMessage(LOG_ALERT, "alert", $message, $context);
    }

    public function critical($message, array $context = array()) {
        $this->addMessage(LOG_CRIT, "critical", $message, $context);
    }

    public function error($message, array $context = array()) {
        $this->addMessage(LOG_ERR, "error", $message, $context);
    }

    public function warning($message, array $context = array()) {
        $this->addMessage(LOG_WARNING, "warning", $message, $context);
    }

    public function notice($message, array $context = array()) {
        $this->addMessage(LOG_NOTICE, "notice", $message, $context);
    }

    public function info($message, array $context = array()) {
        $this->addMessage(LOG_INFO, "info", $message, $context);
    }

    public function debug($message, array $context = array()) {
        $this->addMessage(LOG_DEBUG, "debug", $message, $context);
    }

    public function log($level, $message, array $context = array()) {
        $this->addMessage(LOG_NEWS, "log", $message, $context);
    }
}