<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\LogRecordFormatterInterface;
use Terrazza\Component\Logger\LogRecordValueConverterInterface;
use Terrazza\Component\Logger\Record\LogRecord;

class LogRecordFormatterMock implements LogRecordFormatterInterface {
    /**
     * @param string $tokenKey
     * @param LogRecordValueConverterInterface $valueConverter
     */
    public function pushConverter(string $tokenKey, LogRecordValueConverterInterface $valueConverter): void {}

    /**
     * @param LogRecord $record
     * @param array|null $format
     * @return array
     */
    public function formatRecord(LogRecord $record, array $format=null): array {return [];}
}