<?php

namespace Terrazza\Component\Logger\Converter;

interface IFormattedRecordConverter {
    /**
     * @param array $data
     * @return string
     */
    public function convert(array $data) : string;
}