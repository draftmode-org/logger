<?php
namespace Terrazza\Component\Logger;

use RuntimeException;
use Terrazza\Component\Logger\Record\LogRecord;
use Throwable;

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

    public static array $levels = [
        self::LOG                                   => 'LOG',
        self::DEBUG                                 => 'DEBUG',
        self::INFO                                  => 'INFO',
        self::NOTICE                                => 'NOTICE',
        self::WARNING                               => 'WARNING',
        self::ERROR                                 => 'ERROR',
        self::CRITICAL                              => 'CRITICAL',
        self::ALERT                                 => 'ALERT',
        self::EMERGENCY                             => 'EMERGENCY'
    ];

    private string $loggerName;
    private array $context;
    /**
     * @var ChannelHandlerInterface[]
     */
    private array $channel                          = [];

    /**
     * @var string
     */
    private string $exceptionFile                   = "php://stderr";

    /**
     * @param string $loggerName
     * @param array|null $context
     * @param ChannelHandlerInterface ...$channelHandler
     */
    public function __construct(string $loggerName, ?array $context=null, ChannelHandlerInterface ...$channelHandler) {
        $this->loggerName                           = $loggerName;
        $this->context                              = $context ?? [];
        foreach ($channelHandler as $handler) {
            $this->registerChannel($handler);
        }
    }

    /**
     * @param ChannelHandlerInterface $channelHandler
     * @return LoggerInterface
     */
    public function registerChannel(ChannelHandlerInterface $channelHandler) : LoggerInterface {
        $channelName                                = $channelHandler->getChannel()->getName();
        $this->channel[$channelName]                = $channelHandler;
        return $this;
    }

    /**
     * @param string $channelName
     * @param LogHandlerInterface $logHandler
     * @return LoggerInterface
     */
    public function pushLogHandler(string $channelName, LogHandlerInterface $logHandler) : LoggerInterface {
        if (array_key_exists($channelName, $this->channel)) {
            $channelHandler                         = $this->channel[$channelName];
            $channelHandler->pushLogHandler($logHandler);
            return $this;
        } else {
            throw new RuntimeException("logHandler cannot be pushed, channel ".$channelName." is not registered");
        }
    }

    /**
     *
     */
    public function registerExceptionHandler() : void {
        set_exception_handler([$this, "handleException"]);
    }

    /**
     * @param int $errorTypes
     */
    public function registerErrorHandler(int $errorTypes = -1) : void {
        set_error_handler([$this, "handleError"]);//, $errorTypes);
    }

    /**
     * @param int $display_errors
     */
    public function registerFatalHandler(int $display_errors=0) : void {
        ini_set('display_errors',$display_errors);
        register_shutdown_function([$this, "handleFatalError"]);
    }

    /**
     * @param string $exceptionFile
     */
    public function setExceptionFile(string $exceptionFile) : void {
        $this->exceptionFile                    = $exceptionFile;
    }

    /**
     *
     */
    public function handleFatalError() : void {
        if ($error = error_get_last()) {
            $this->handleError(
                $error["type"],
                $error["message"],
                $error["file"],
                $error["line"]
            );
        }
    }

    /**
     * @param int $errorCode
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     */
    public function handleError(int $errorCode, string $message, string $file=null, int $line=null) : void {
        $context                                    = [
            "file"                                  => $file,
            "line"                                  => $line,
        ];
        switch ($errorCode) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $this->notice($message, $context);
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $this->warning($message, $context);
                break;
            default:
                $this->emergency("$message (#$errorCode) {$file} {$line}", $context);
        }
    }

    /**
     * @param Throwable $exception
     */
    public function handleException(Throwable $exception) : void {
        $this->emergency("application exception: ".$exception->getMessage());//, ["exception" => $exception]);
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
        $record = LogRecord::createRecord(
            $this->loggerName,
            $logLevel,
            $message,
            $context ?? []
        );

        try {
            foreach ($this->channel as $channelHandler) {
                $channelHandler->handleRecord($record);
            }
        } catch (Throwable $exception) {
            @file_put_contents($this->exceptionFile, join(" ", [
                (new \DateTime())->format("Y-m-d H:i:s.u"),
                "[".self::$levels[self::EMERGENCY]."]",
                $exception->getMessage()
            ]), FILE_APPEND);
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