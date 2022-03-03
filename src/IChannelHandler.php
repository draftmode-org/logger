<?php
namespace Terrazza\Component\Logger;

interface IChannelHandler {
    /**
     * @param int $logLevel
     * @param array $format
     * @param ILoggerFilter|null $filter
     */
    public function pushHandler(int $logLevel, array $format, ?ILoggerFilter $filter=null) : void;
}