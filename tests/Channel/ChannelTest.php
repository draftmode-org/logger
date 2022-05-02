<?php

namespace Terrazza\Component\Logger\Tests\Channel;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel\Channel;
use Terrazza\Component\Logger\Tests\_Mocks\LogRecordFormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\LogWriterMock;

class ChannelTest extends TestCase {
    function test() {
        $writer     = new LogWriterMock();
        $formatter  = new LogRecordFormatterMock();
        $channel    = new Channel($channelName="name", $writer, $formatter);
        $this->assertEquals([
            $channelName,
            $writer,
            $formatter
        ],[
            $channel->getName(),
            $channel->getWriter(),
            $channel->getFormatter()
        ]);
    }
}