<?php

namespace Terrazza\Component\Logger\Tests\Common;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\ChannelHandler;
use Terrazza\Component\Logger\FormatterInterface;
use Terrazza\Component\Logger\Logger;
use Terrazza\Component\Logger\LogRecord;
use Terrazza\Component\Logger\Writer\LogStreamWriter;

class ChannelHandlerTest extends TestCase
{
    function test() {
        $writer     = new LogStreamWriter("php://stdout");
        $formatter  = new ChannelHandlerTestFormatter;
        $channel    = new ChannelHandler($channelName="name", $writer, $formatter);
        $record     = LogRecord::createRecord("loggerName", Logger::WARNING, "message");
        $this->assertEquals([
            $channelName,
        ],[
            $channel->getName(),
        ]);
        $channel->write($record);
    }
}

class ChannelHandlerTestFormatter implements FormatterInterface {

    public function format(LogRecord $logRecord) : string {
        return "";
    }
}