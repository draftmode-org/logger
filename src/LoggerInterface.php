<?php
namespace Terrazza\Component\Logger;
use \Psr\Log\LoggerInterface as PSRLoggerInterface;

interface LoggerInterface extends PSRLoggerInterface {
    /**
     * @param HandlerInterface $handler
     * @return LoggerInterface
     */
    public function withHandler(HandlerInterface $handler) : LoggerInterface;

    /**
     * @param string $namespace
     * @return LoggerInterface
     */
    public function withNamespace(string $namespace) : LoggerInterface;

    /**
     * @param string $method
     * @return LoggerInterface
     */
    public function withMethod(string $method) : LoggerInterface;

    /**
     * @param string $key
     * @return bool
     */
    public function hasContextKey(string $key) : bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function getContextKey(string $key);
}