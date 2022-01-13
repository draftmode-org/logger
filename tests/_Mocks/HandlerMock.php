<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\IChannelHandler;
use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\Formatter\ArrayFormatter;
use Terrazza\Component\Logger\Handler\HandlerPattern;
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\IRecordTokenReader;
use Terrazza\Component\Logger\Normalizer\NormalizeFlat;
use Terrazza\Component\Logger\RecordToken\RecordTokenReader;
use Terrazza\Component\Logger\RecordToken\RecordTokenValueDate;
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
    public static function getTokenReader(string $dateFormat) : IRecordTokenReader {
        return new RecordTokenReader(["Date" => new RecordTokenValueDate($dateFormat)]);
    }

    public static function getChannel(string $dateFormat) : IChannel {
        return new Channel(
            "channel",
            new StreamWriter(self::stream),
            //new ArrayFormatter(self::getTokenReader($dateFormat), new NormalizeFlat("|"))
            new ArrayFormatter(new RecordTokenReader, new NormalizeFlat("|"))
        );
    }

    public static function getChannelHandler(string $dateFormat="Y-m-d") : IChannelHandler {
        return new ChannelHandler(self::getChannel($dateFormat));
    }

    public static function getSingleHandler(HandlerPattern $pattern, array $format, string $dateFormat="Y-m-d") : IHandler {
        return new SingleHandler(
            $pattern,
            self::getChannel($dateFormat),
            $format
        );
    }
}