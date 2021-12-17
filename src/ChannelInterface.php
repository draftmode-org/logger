<?php
namespace Terrazza\Component\Logger;

interface ChannelInterface {
    public function getName() : string;

    /**
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface;

    /**
     * @return LogWriterInterface
     */
    public function getWriter() : LogWriterInterface;
}