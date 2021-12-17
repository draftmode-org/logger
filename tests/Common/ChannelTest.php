<?php

namespace Terrazza\Component\Logger\Tests\Common;

use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Tests\_Mocks\FormatterMock;
use Terrazza\Component\Logger\Tests\_Mocks\WriterMock;

class ChannelTest extends TestCase {
    function test() {
        $writer     = new WriterMock();
        $formatter  = new FormatterMock();
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