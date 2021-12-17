<?php
namespace Terrazza\Component\Logger;

interface IChannel {
    public function getName() : string;

    /**
     * @return IFormatter
     */
    public function getFormatter() : IFormatter;

    /**
     * @return IWriter
     */
    public function getWriter() : IWriter;
}