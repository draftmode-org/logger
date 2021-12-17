<?php

namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Handler\HandlerPattern;

interface IChannelHandler {
    /**
     * @param HandlerPattern $pattern
     * @param mixed $format
     */
    public function pushHandler(HandlerPattern $pattern, $format) : void;
}