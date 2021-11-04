<?php
namespace Terrazza\Component\Logger;

interface LogInterface extends \Psr\Log\LoggerInterface {
    public function withHandler(LogHandlerInterface $handler) : LogInterface;
    public function withMethod(string $method) : LogInterface;
}