<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;

use Terrazza\Component\Logger\Formatter\LogRecordFormatter;
use Terrazza\Component\Logger\Handler\ChannelHandler;
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

    public static function getContent(string $stream=null) :?string {
        if (file_exists($stream ?? self::stream)) {
            $content = trim(file_get_contents($stream ?? self::stream));
            @unlink($stream ?? self::stream);
        } else {
            $content = null;
        }
        return $content;
    }

    public static function getChannelHandler(?array $format=null, string $stream=null, ?array $converter=null) : ChannelHandlerInterface {
        $formatter = new LogRecordFormatter(new NonScalarJsonConverter(), $format ?? ["{LoggerName} {Level} {Message}"]);
        foreach ($converter ?? [] as $tKey => $tCallback) {
            $formatter->pushConverter($tKey, $tCallback);
        }
        return new ChannelHandler(
            new LogStreamFileWriter(new FormattedRecordConverterMock(), $stream ?? self::stream),
            $formatter,
            null
        );
    }

    public static function getLogHandler(int $logLevel, ?array $format=null, ?LogHandlerFilterInterface $filter=null) : LogHandlerInterface {
        return new LogHandler(
            $logLevel,
            $format,
            $filter
        );
    }
}