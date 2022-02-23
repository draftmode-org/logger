<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\IRecordFormatter;
use Terrazza\Component\Logger\IRecordValueConverter;
use Terrazza\Component\Logger\Record;

class FormatterMock implements IRecordFormatter {
    public function withFormat(array $format): IRecordFormatter {return $this;}
    public function pushConverter(string $tokenKey, IRecordValueConverter $valueConverter): void {}
    public function formatRecord(Record $record): array {return [];}
}