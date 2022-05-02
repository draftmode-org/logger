<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\LogHandlerFilter;
use Terrazza\Component\Logger\LogHandlerFilterInterface;

class LoggerFilterTest extends TestCase {

    function testNoFilter() {
        $filter = new LogHandlerFilter();
        $this->assertTrue($filter->isHandling("myNamespace"));
    }

    function testInclude() {
        $filter = new LogHandlerFilter(["Terrazza/Component"]);
        $this->assertEquals([
            true,
            false
        ],[
            $filter->isHandling(__NAMESPACE__),
            $filter->isHandling("__NAMESPACE__")
        ]);
    }

    function testExclude() {
        $filter = new LogHandlerFilter(null, ["Terrazza/Component"]);
        $this->assertEquals([
            false,
            true,
        ],[
            $filter->isHandling(__NAMESPACE__),
            $filter->isHandling("__NAMESPACE__")
        ]);
    }

    function testStart() {
        $filter = new LogHandlerFilter(null, null, ["Terrazza\Component\Logger\Tests\Common"]);
        $this->assertEquals([
            false,
            false,
            true,
            true
        ],[
            $filter->isHandling("Terrazza\Component\Logger"),
            $filter->isHandling("Terrazza\Component\Logger\Tests"),
            $filter->isHandling("Terrazza\Component\Logger\Tests\Common"),
            $filter->isHandling("LoggerFilterTest"),
        ]);
    }

    function testStartExclude() {
        $filter = new LogHandlerFilter(null, ["LoggerFilterTest"], ["Terrazza\Component\Logger\Tests\Common"]);
        $this->assertEquals([
            false,
            false,
            true,
            false
        ],[
            $filter->isHandling("Terrazza\Component\Logger"),
            $filter->isHandling("Terrazza\Component\Logger\Tests"),
            $filter->isHandling("Terrazza\Component\Logger\Tests\Common"),
            $filter->isHandling("LoggerFilterTest"),
        ]);
    }
}