<?php
namespace Terrazza\Component\Logger;
use Terrazza\Component\Logger\Writer\WriterException;

interface IHandler {
    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record) : bool;

    /**
     * @param Record $record
     * @throws WriterException
     */
    public function writeRecord(Record $record) : void;

    public function close() : void;
}