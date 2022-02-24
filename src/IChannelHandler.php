<?php
namespace Terrazza\Component\Logger;

interface IChannelHandler {
    /**
     * @param int $logLevel
     * @param array $format
     */
    public function pushHandler(int $logLevel, array $format) : void;
}