<?php

namespace Terrazza\Component\Logger;

interface IFormatter {
    /**
     * @param mixed $format
     * @return IFormatter
     */
    public function withFormat($format) : IFormatter;

    /**
     * @param Record $record
     * @return string
     */
    public function formatRecord(Record $record) : string;
}