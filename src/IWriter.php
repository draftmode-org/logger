<?php

namespace Terrazza\Component\Logger;

interface IWriter {
    public function write(string $record) : void;
}