<?php
namespace Terrazza\Component\Logger;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

interface LoggerInterface extends PsrLoggerInterface {
    /**
     *
     */
    public function registerExceptionHandler() : void;

    /**
     * @param int $errorTypes
     */
    public function registerErrorHandler(int $errorTypes = -1) : void;

    /**
     * @param int $display_errors
     */
    public function registerFatalHandler(int $display_errors=0) : void;

    /**
     * @param ChannelHandlerInterface $channelHandler
     * @return LoggerInterface
     */
    public function registerChannel(ChannelHandlerInterface $channelHandler) : LoggerInterface;

    /**
     * @param string $channelName
     * @param LogHandlerInterface $logHandler
     * @return LoggerInterface
     */
    public function pushLogHandler(string $channelName, LogHandlerInterface $logHandler) : LoggerInterface;

    /**
     * @param string $key
     * @return bool
     */
    public function hasContextKey(string $key) : bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function getContextByKey(string $key);
}