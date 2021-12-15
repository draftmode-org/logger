<?php
namespace Terrazza\Component\Logger\Writer;
use Terrazza\Component\Logger\LogWriterInterface;

class LogStreamWriter implements LogWriterInterface {
    private string $stream;
    public function __construct(string $stream) {
        $this->stream = $stream;
    }

    public function write(string $record) : void {
        if (@file_put_contents($this->stream, $record.PHP_EOL) === false) {
            throw new LogWriterException($this->stream);
        }
    }
}