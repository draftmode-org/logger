<?php
namespace Terrazza\Component\Logger\Tests\Writer;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Writer\StreamWriter;
use Terrazza\Component\Logger\Writer\WriterException;

class StreamWriterTest extends TestCase {
    public string $stream="tests/Writer/stream.txt";
    function setUp(): void {
        @unlink($this->stream);
    }
    function tearDown(): void {
        @unlink($this->stream);
    }

    function testSuccessful() {
        $writer = new StreamWriter($this->stream);
        $writer->write($record="myMessage");
        $this->assertEquals(
            $record.PHP_EOL,
            file_get_contents($this->stream)
        );
    }

    function testFailure() {
        $writer = new StreamWriter("folder/file.txt");
        $this->expectException(WriterException::class);
        $writer->write("myMessage");
    }
}