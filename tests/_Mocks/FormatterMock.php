<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\FormatterInterface;
use Terrazza\Component\Logger\Record;

class FormatterMock implements FormatterInterface {
    public function withFormat($format): FormatterInterface {return $this;}
    public function formatRecord(Record $record): string {return "-";}
}