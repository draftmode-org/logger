<?php
namespace Terrazza\Component\Logger;
use Terrazza\Component\Logger\Handler\StreamHandlerWriteException;

interface IHandler {
    /**
     * @param Record $record
     * @return bool
     */
    public function isHandling(Record $record) : bool;

    /**
     * @param Record $record
     * @throws StreamHandlerWriteException
     */
    public function writeRecord(Record $record) : void;

    public function close() : void;
}