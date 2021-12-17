<?php
namespace Terrazza\Component\Logger;
use Psr\Log\LoggerInterface;

interface ILogger extends LoggerInterface {
    /**
     * @param IHandler $handler
     * @return ILogger
     */
    public function withHandler(IHandler $handler) : ILogger;

    /**
     * @param string $namespace
     * @return ILogger
     */
    public function withNamespace(string $namespace) : ILogger;

    /**
     * @param string $method
     * @return ILogger
     */
    public function withMethod(string $method) : ILogger;

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