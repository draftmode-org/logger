<?php

namespace Terrazza\Component\Logger\RecordToken;
use DateTime;
use Terrazza\Component\Logger\IRecordTokenValueConverter;

class RecordTokenValueDate implements IRecordTokenValueConverter {
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