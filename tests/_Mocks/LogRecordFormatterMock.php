<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\LogRecordValueConverterInterface;
use Terrazza\Component\Logger\Record\LogRecord;

class LogRecordFormatterMock implements LogRecordFormatterInterface {
    public function withFormat(array $format): LogRecordFormatterInterface {return $this;}
    public function pushConverter(string $tokenKey, LogRecordValueConverterInterface $valueConverter): void {}
    public function formatRecord(LogRecord $record): array {return [];}
}