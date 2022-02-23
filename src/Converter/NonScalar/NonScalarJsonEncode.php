<?php
namespace Terrazza\Component\Logger\Converter\NonScalar;
use Terrazza\Component\Logger\Converter\INonScalarConverter;

class NonScalarJsonEncode implements INonScalarConverter {
    /**
     * @param string $tKey
     * @param array|object $content
     * @return string
     */
    public function getValue(string $tKey, $content) : string {
        return $tKey.":".json_encode($content);
    }
}