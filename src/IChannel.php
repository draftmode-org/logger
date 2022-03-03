<?php
namespace Terrazza\Component\Logger;

interface IChannel {
    public function getName() : string;

    /**
     * @return IRecordFormatter
     */
    public function getFormatter() : IRecordFormatter;

    /**
     * @return LoggerFilter|null
     */
    public function getFilter() : ?LoggerFilter;

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter;
}