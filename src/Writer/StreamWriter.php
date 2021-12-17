<?php
namespace Terrazza\Component\Logger\Writer;
use Terrazza\Component\Logger\IWriter;

class StreamWriter implements IWriter {
    private string $stream;
    public function __construct(string $stream) {
        $this->stream = $stream;
    }

    public function write(string $record) : void {
        if (@file_put_contents($this->stream, $record.PHP_EOL) === false) {
            throw new WriterException($this->stream);
        }
    }
}