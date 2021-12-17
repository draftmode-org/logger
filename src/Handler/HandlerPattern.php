<?php

namespace Terrazza\Component\Logger\Handler;

class HandlerPattern {
    private ?int $logLevel;
    public function __construct(?int $logLevel=null) {
        $this->logLevel                             = $logLevel;
    }

    /**
     * @return int|null
     */
    public function getLogLevel() :?int {
        return $this->logLevel;
    }
}