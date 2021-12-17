<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\LogWriterInterface;

class WriterMock implements LogWriterInterface {
    public function write(string $record): void {}
}