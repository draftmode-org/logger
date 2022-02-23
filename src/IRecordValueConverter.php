<?php

namespace Terrazza\Component\Logger;

interface IRecordValueConverter {
    /**
     * @param mixed $value
     * @return mixed|null
     */
    public function getValue($value);
}