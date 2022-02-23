<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\IWriter;

class WriterMock implements IWriter {
    public function write(array $record): void {}
}