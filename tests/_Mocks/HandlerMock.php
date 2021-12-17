<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\IChannelHandler;
use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Handler\SingleIHandler;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;
use Terrazza\Component\Logger\Writer\StreamWriter;

class HandlerMock {
    CONST stream="tests/Writer/stream.txt";
    public static function setUp(): void {
        @unlink(self::stream);
    }
    public static function tearDown(): void {
        @unlink(self::stream);
    }
    public static function getContent() : string {
        return trim(file_get_contents(self::stream));
    }

    public static function getChannel() : IChannel {
        return new Channel(
            "channel",
            new StreamWriter(self::stream),
            new ArrayFormatter("d.m.Y", new NormalizeFlat("|"))
        );
    }

    public static function getChannelHandler() : IChannelHandler {
        return new ChannelHandler(self::getChannel());
    }

    public static function getSingleHandler(HandlerPattern $pattern, array $format) : IHandler {
        return new SingleIHandler(
            $pattern,
            self::getChannel(),
            $format
        );
    }
}