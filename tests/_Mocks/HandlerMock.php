<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\HandlerInterface;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;
use Terrazza\Component\Logger\Writer\LogStreamWriter;

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

    public static function getChannel() : ChannelInterface {
        return new Channel(
            "channel",
            new LogStreamWriter(self::stream),
            new ArrayFormatter("d.m.Y", new NormalizeFlat("|"))
        );
    }

    public static function getChannelHandler() : ChannelHandlerInterface {
        return new ChannelHandler(self::getChannel());
    }

    public static function getSingleHandler(HandlerPattern $pattern, array $format) : HandlerInterface {
        return new SingleHandler(
            $pattern,
            self::getChannel(),
            $format
        );
    }
}