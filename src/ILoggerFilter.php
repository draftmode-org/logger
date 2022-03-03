<?php
namespace Terrazza\Component\Logger;

interface ILoggerFilter {
    public function isHandling(string $callerNamespace) : bool;
}