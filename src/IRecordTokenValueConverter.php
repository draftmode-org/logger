<?php

namespace Terrazza\Component\Logger;

interface IRecordTokenValueConverter {
    /**
     * @param mixed $value
     * @return mixed|null
     */
    public function getValue($value);
}