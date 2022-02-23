<?php

namespace Terrazza\Component\Logger\Utility\RecordValueConverter;
use DateTime;
use Terrazza\Component\Logger\IRecordValueConverter;

class RecordValueDate implements IRecordValueConverter {
    private string $dateFormat;

    public function __construct(string $dateFormat="Y-m-d H:i:s.u") {
        $this->dateFormat                           = $dateFormat;
    }

    /**
     * @param DateTime $value
     * @return string
     */
    public function getValue($value) {
        return $value->format($this->dateFormat);
    }
}