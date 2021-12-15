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
     * @param array $context
     * @return LoggerInterface
     */
    public function withContext(array $context) : LoggerInterface;
}