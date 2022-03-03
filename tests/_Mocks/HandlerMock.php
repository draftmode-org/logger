<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Channel;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonEncode;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\IChannelHandler;
use Terrazza\Component\Logger\IChannel;
use Terrazza\Component\Logger\Formatter\RecordFormatter;
use Terrazza\Component\Logger\Handler\SingleHandler;
use Terrazza\Component\Logger\IHandler;
use Terrazza\Component\Logger\ILoggerFilter;
use Terrazza\Component\Logger\Writer\StreamFile;

class HandlerMock {
    CONST stream="tests/Writer/stream.txt";
    public static function setUp(): void {
        @unlink(self::stream);
    }
    public static function tearDown(): void {
        @unlink(self::stream);
    }
    public static function getContent() :?string {
        if (file_exists(self::stream)) {
            return trim(file_get_contents(self::stream));
        } else {
            return null;
        }
    }

    public static function getChannel() : IChannel {
        return new Channel(
            "channel",
            new StreamFile(new FormattedRecordConverterMock(), self::stream),
            new RecordFormatter(new NonScalarJsonEncode(), [])
        );
    }

    public static function getChannelHandler(string $dateFormat="Y-m-d") : IChannelHandler {
        return new ChannelHandler(self::getChannel($dateFormat));
    }

    public static function getSingleHandler(int $logLevel, array $format, ?ILoggerFilter $filter=null) : IHandler {
        return new SingleHandler(
            $logLevel,
            self::getChannel(),
            $format,
            $filter
        );
    }
}