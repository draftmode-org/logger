<?php

namespace Terrazza\Component\Logger\Converter;

interface INonScalarConverter {
    /**
     * @param string $tKey
     * @param object|array $content
     * @return string
     */
    public function getValue(string $tKey, $content) : string;
}