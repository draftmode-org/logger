<?php
namespace Terrazza\Component\Logger;

interface LoggerInterface extends \Psr\Log\LoggerInterface {
    public function withHandler(LoggerHandlerInterface $handler) : LoggerInterface;
    public function withMethod(string $method) : LoggerInterface;
}