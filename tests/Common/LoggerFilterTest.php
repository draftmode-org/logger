<?php
namespace Terrazza\Component\Logger\Tests\Common;
use PHPUnit\Framework\TestCase;
use Terrazza\Component\Logger\LoggerFilter;

class LoggerFilterTest extends TestCase {

    function testNoFilter() {
        $filter = new LoggerFilter();
        $this->assertTrue($filter->isHandling("myNamespace"));
    }

    function testInclude() {
        $filter = new LoggerFilter(["Terrazza/Component"]);
        $this->assertEquals([
            true,
            false
        ],[
            $filter->isHandling(__NAMESPACE__),
            $filter->isHandling("__NAMESPACE__")
        ]);
    }

    function testExclude() {
        $filter = new LoggerFilter(null, ["Terrazza/Component"]);
        $this->assertEquals([
            false,
            true,
        ],[
            $filter->isHandling(__NAMESPACE__),
            $filter->isHandling("__NAMESPACE__")
        ]);
    }

    function testStart() {
        $filter = new LoggerFilter(null, null, ["Terrazza\Component\Logger\Tests\Common"]);
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
        $filter = new LoggerFilter(null, ["LoggerFilterTest"], ["Terrazza\Component\Logger\Tests\Common"]);
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