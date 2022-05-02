<?php

namespace Terrazza\Component\Logger;

interface LogWriterInterface {
    public function write(array $record) : void;
}