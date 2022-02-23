<?php

namespace Terrazza\Component\Logger;

interface IRecordFormatter {
    /**
     * @param array $format
     * @return IRecordFormatter
     */
    public function withFormat(array $format): IRecordFormatter;

    /**
     * @param string $tokenKey
     * @param IRecordValueConverter $valueConverter
     */
    public function pushConverter(string $tokenKey, IRecordValueConverter $valueConverter) : void;

    /**
     * @param Record $record
     * @return array
     */
    public function formatRecord(Record $record) : array;
}