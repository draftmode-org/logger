<?php

namespace Terrazza\Component\Logger;

interface IWriter {
    public function write(array $record) : void;
}