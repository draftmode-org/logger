<?php
namespace Terrazza\Component\Logger;

interface ChannelHandlerInterface {
    public function getName() : string;
    public function write(LogRecord $record) : void;
}