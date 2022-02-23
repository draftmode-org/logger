<?php
namespace Terrazza\Component\Logger;

interface IChannel {
    public function getName() : string;

    /**
     * @return IRecordFormatter
     */
    public function getFormatter() : IRecordFormatter;

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter;
}