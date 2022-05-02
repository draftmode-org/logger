<?php
namespace Terrazza\Component\Logger\Tests\_Mocks;
use Terrazza\Component\Logger\Converter\FormattedRecordConverterInterface;

class FormattedRecordConverterMock implements FormattedRecordConverterInterface {
    public function convert(array $data): string {
        return join("|", $data);
    }
}