<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Channel\Channel;
use Terrazza\Component\Logger\Formatter\LogRecordFormatter;
use Terrazza\Component\Logger\Handler\ChannelHandler;
use Terrazza\Component\Logger\ChannelInterface;
use Terrazza\Component\Logger\Converter\NonScalar\NonScalarJsonConverter;
use Terrazza\Component\Logger\ChannelHandlerInterface;
use Terrazza\Component\Logger\Handler\LogHandler;
use Terrazza\Component\Logger\LogHandlerInterface;
use Terrazza\Component\Logger\LogHandlerFilterInterface;
use Terrazza\Component\Logger\Writer\LogStreamFileWriter;

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

    public static function getChannel() : ChannelInterface {
        return new Channel(
            "channel",
            new LogStreamFileWriter(new FormattedRecordConverterMock(), self::stream),
            new LogRecordFormatter(new NonScalarJsonConverter(), [])
        );
    }

    public static function getChannelHandler(LogHandlerInterface ...$logHandler) : ChannelHandlerInterface {
        return new ChannelHandler(self::getChannel(), ...$logHandler);
    }

    public static function getLogHandler(int $logLevel, ?array $format=null, ?LogHandlerFilterInterface $filter=null) : LogHandlerInterface {
        return new LogHandler(
            $logLevel,
            $format,
            $filter
        );
    }
}