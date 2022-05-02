<?php
namespace Terrazza\Component\Logger;

interface LogHandlerFilterInterface {
    public function isHandling(string $callerNamespace) : bool;
}