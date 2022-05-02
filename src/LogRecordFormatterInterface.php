<?php

namespace Terrazza\Component\Logger;

use Terrazza\Component\Logger\Record\LogRecord;

interface LogRecordFormatterInterface {
    /**
     * @param array $format
     * @return LogRecordFormatterInterface
     */
    public function withFormat(array $format): LogRecordFormatterInterface;

    /**
     * @param string $tokenKey
     * @param LogRecordValueConverterInterface $valueConverter
     */
    public function pushConverter(string $tokenKey, LogRecordValueConverterInterface $valueConverter) : void;

    /**
     * @param LogRecord $record
     * @return array
     */
    public function formatRecord(LogRecord $record) : array;
}