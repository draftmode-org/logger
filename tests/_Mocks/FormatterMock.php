<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\IFormatter;
use Terrazza\Component\Logger\Record;

class FormatterMock implements IFormatter {
    public function withFormat($format): IFormatter {return $this;}
    public function formatRecord(Record $record): string {return "-";}
}