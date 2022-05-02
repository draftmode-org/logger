<?php
namespace Terrazza\Component\Logger\Writer;
use Terrazza\Component\Logger\Converter\FormattedRecordConverterInterface;
use Terrazza\Component\Logger\LogWriterInterface;

class LogStreamFileWriter implements LogWriterInterface {
    private FormattedRecordConverterInterface $converter;
    private string $filename;
    private int $flags;

    public function __construct(FormattedRecordConverterInterface $converter, string $filename, int $flags=0) {
        $this->filename 							= $filename;
        $this->flags					            = $flags;
        $this->converter 							= $converter;
    }

    /**
     * @param array $record
     */
    public function write(array $record) : void {
        if (@file_put_contents(
            $this->filename,
            $this->converter->convert($record).PHP_EOL,
            $this->flags
        ) === false) {
            throw new LogWriterException($this->filename);
        }
    }
}