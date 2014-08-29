<?php
namespace MatryoshkaTest\Model;

use Matryoshka\Model\Hydrator\Strategy\DateTimeStrategy;
class DateTimeStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function test__construct()
    {
        $strategy = new DateTimeStrategy(\DateTime::W3C);
        $this->assertSame(\DateTime::W3C, $strategy->getFormat());
    }

    public function testGetSetFormat()
    {
        $strategy = new DateTimeStrategy();
        $this->assertInstanceOf('\Matryoshka\Model\Hydrator\Strategy\DateTimeStrategy', $strategy->setFormat(\DateTime::COOKIE));
        $this->assertSame(\DateTime::COOKIE, $strategy->getFormat());
    }

    public function testHydrate()
    {
        $strategy = new DateTimeStrategy();

        $hydratedValue = $strategy->hydrate('2014-11-11T11:11:11+0100');
        $this->assertInstanceOf('DateTime', $hydratedValue);

        $hydratedValue = $strategy->hydrate(null);
        $this->assertNull($hydratedValue);
    }

    public function testExtract()
    {
        $strategy = new DateTimeStrategy();

        $extractedValue = $strategy->extract(new \DateTime('2014-11-11T11:11:11+0100'));
        $this->assertInternalType('string', $extractedValue);
        $this->assertSame('2014-11-11T11:11:11+0100', $extractedValue);

        $extractedValue = $strategy->hydrate(null);
        $this->assertNull($extractedValue);
    }
}