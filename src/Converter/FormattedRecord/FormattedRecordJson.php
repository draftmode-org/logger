<?php
namespace Terrazza\Component\Logger\Converter\FormattedRecord;
use Terrazza\Component\Logger\Converter\IFormattedRecordConverter;

class FormattedRecordJson implements IFormattedRecordConverter {
    /**
     * @param array $data
     * @return string
     */
    public function convert(array $data) : string {
        return json_encode($data);
    }
}