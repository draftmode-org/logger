<?php
namespace Terrazza\Component\Logger;

use Psr\Log\LoggerInterface;

interface LogInterface extends LoggerInterface {
    /**
     * @param LogHandlerInterface $handler
     * @return LogInterface
     */
    public function withHandler(LogHandlerInterface $handler) : LogInterface;

    /**
     * @param string $namespace
     * @return LogInterface
     */
    public function withNamespace(string $namespace) : LogInterface;

    /**
     * @param string $method
     * @return LogInterface
     */
    public function withMethod(string $method) : LogInterface;

    /**
     * @param array $context
     * @return LogInterface
     */
    public function withContext(array $context) : LogInterface;

    /**
     * @param string $contextKey
     * @param mixed $value
     */
    public function addContextValue(string $contextKey, $value) : void;

    /**
     * @param string $contextKey
     * @return bool
     */
    public function hasContextKey(string $contextKey) : bool;

    /**
     * @param string $contextKey
     * @return mixed
     */
    public function getContextValue(string $contextKey);
}