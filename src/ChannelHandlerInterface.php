<?php

namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Handler\HandlerPattern;

interface ChannelHandlerInterface {
    /**
     * @param HandlerPattern $pattern
     * @param mixed $format
     */
    public function pushHandler(HandlerPattern $pattern, $format) : void;
}