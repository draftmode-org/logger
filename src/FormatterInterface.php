<?php

namespace Terrazza\Component\Logger;

interface FormatterInterface {
    /**
     * @param mixed $format
     * @return FormatterInterface
     */
    public function withFormat($format) : FormatterInterface;

    /**
     * @param Record $record
     * @return string
     */
    public function formatRecord(Record $record) : string;
}