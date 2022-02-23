<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\Converter\IFormattedRecordConverter;

class FormattedRecordConverterMock implements IFormattedRecordConverter {
    public function convert(array $data): string {
        return join("|", $data);
    }
}