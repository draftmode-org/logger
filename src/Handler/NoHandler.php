<?php
namespace Terrazza\Component\Logger\Handler;

use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\LogRecord;

class NoHandler implements LogHandlerInterface {
    public function isHandling(LogRecord $logRecord): bool { return false;}
    public function hasLogPatterns(): bool { return false;}
    public function write(LogRecord $logRecord) : void {}
}