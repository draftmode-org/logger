<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\LogWriterInterface;

class LogWriterMock implements LogWriterInterface {
    public function write(array $record): void {}
}