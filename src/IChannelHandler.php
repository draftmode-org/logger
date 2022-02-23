<?php

namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Handler\HandlerPattern;

interface IChannelHandler {
    /**
     * @param HandlerPattern $pattern
     * @param array $format
     */
    public function pushHandler(HandlerPattern $pattern, array $format) : void;
}