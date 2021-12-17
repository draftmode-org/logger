<?php
namespace Terrazza\Component\Logger\Tests\Handler;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\Handler\HandlerPattern;

class HandlerPatternTest extends TestCase {
    function test() {
        $pattern = new HandlerPattern($logLevel=2);
        $this->assertEquals([
            $logLevel,
        ],[
            $pattern->getLogLevel(),
        ]);
    }
}