<?php

namespace Terrazza\Component\Logger;

interface LogWriterInterface {
    public function write(string $record) : void;
}