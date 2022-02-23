<?php
namespace Terrazza\Component\Logger\Converter\FormattedRecord;
use Terrazza\Component\Logger\Converter\IFormattedRecordConverter;

class FormattedRecordFlat implements IFormattedRecordConverter {
    private string $delimiter;
    public function __construct(string $delimiter) {
        $this->delimiter = $delimiter;
    }
    /**
     * @param array $data
     * @return string
     */
    public function convert(array $data) : string {
        $lines										= [];
        foreach ($data as $dKey => $dValue) {
            if (is_string($dValue)) {
                $lines[]							= $dValue;
            }
            else {
                $lines[] 							= $dKey. PHP_EOL . json_encode($dValue, JSON_PRETTY_PRINT);
            }
        }
        return implode($this->delimiter, $lines);
    }
}