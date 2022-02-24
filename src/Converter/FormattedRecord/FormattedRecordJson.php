<?php
namespace Terrazza\Component\Logger\Converter\FormattedRecord;
use Terrazza\Component\Logger\Converter\IFormattedRecordConverter;

class FormattedRecordJson implements IFormattedRecordConverter {
    private int $encodingFlags;
    public function __construct(int $encodingFlags=0) {
        $this->encodingFlags                        = $encodingFlags;
    }
    /**
     * @param array $data
     * @return string
     */
    public function convert(array $data) : string {
        return json_encode($data, $this->encodingFlags);
    }
}