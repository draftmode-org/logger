<?php

namespace Terrazza\Component\Logger;

interface LogRecordValueConverterInterface {
    /**
     * @param mixed $value
     * @return mixed|null
     */
    public function getValue($value);
}