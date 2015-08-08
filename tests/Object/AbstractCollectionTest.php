<?php
namespace MatryoshkaTest\Model\Object;

use Matryoshka\Model\Object\AbstractCollection;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\ArrayObject;
use MatryoshkaTest\Model\TestAsset\ArrayObjectIterator;

/**
 * Class AbstractCollectionTest
 */
class AbstractCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $abstractCollection;

    public function setUp()
    {
        $this->abstractCollection = $this->getMockForAbstractClass(AbstractCollection::class);
    }

    public function testGetHydrator()
    {
        $this->assertInstanceOf(ArraySerializable::class, $this->abstractCollection->getHydrator());
    }

    public function testValidateData()
    {
        $this->abstractCollection->expects($this->any())->method('validateValue')->with('foo')->willReturn(null);
        $this->abstractCollection->validateData(['foo', 'foo']);
    }

    public function testOffsetSet()
    {
        $valueObject = new \stdClass();
        $this->abstractCollection->expects($this->any())->method('validateValue')->with($valueObject)->willReturn(null);
        $this->abstractCollection->offsetSet(0, $valueObject);
    }

    public function testAppend()
    {
        $valueObject = new \stdClass();
        $this->abstractCollection->expects($this->any())->method('validateValue')->with($valueObject)->willReturn(null);
        $this->abstractCollection->append($valueObject);
    }

    public function testExchangeArray()
    {
        $ar = $this->getMockForAbstractClass(AbstractCollection::class, [['foo' => 'bar']]);
        $ar->expects($this->atLeastOnce())->method('validateValue')->withAnyParameters()->willReturn(null);

        $old = $ar->exchangeArray(['bar' => 'baz']);
        $this->assertSame(['foo' => 'bar'], $old);
        $this->assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayPhpArrayObject()
    {
        $ar = $this->getMockForAbstractClass(AbstractCollection::class, [['foo' => 'bar']]);
        $ar->expects($this->atLeastOnce())->method('validateValue')->withAnyParameters()->willReturn(null);

        $old = $ar->exchangeArray(new \ArrayObject(['bar' => 'baz']));
        $this->assertSame(['foo' => 'bar'], $old);
        $this->assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayStdlibArrayObject()
    {
        $ar = $this->getMockForAbstractClass(AbstractCollection::class, [['foo' => 'bar']]);
        $ar->expects($this->atLeastOnce())->method('validateValue')->withAnyParameters()->willReturn(null);

        $old = $ar->exchangeArray(new ArrayObject(['bar' => 'baz']));
        $this->assertSame(['foo' => 'bar'], $old);
        $this->assertSame(['bar' => 'baz'], $ar->getArrayCopy());
    }

    public function testExchangeArrayTestAssetIterator()
    {
        $ar = $this->getMockForAbstractClass(AbstractCollection::class);
        $ar->expects($this->atLeastOnce())->method('validateValue')->withAnyParameters()->willReturn(null);
        $ar->exchangeArray(new ArrayObjectIterator(['foo' => 'bar']));

        // make sure it does what php array object does:
        $ar2 = new \ArrayObject();
        $ar2->exchangeArray(new ArrayObjectIterator(['foo' => 'bar']));

        $this->assertEquals($ar2->getArrayCopy(), $ar->getArrayCopy());
    }

    public function testExchangeArrayArrayIterator()
    {
        $ar = $this->getMockForAbstractClass(AbstractCollection::class, [['foo' => 'bar']]);
        $ar->expects($this->atLeastOnce())->method('validateValue')->withAnyParameters()->willReturn(null);

        $ar->exchangeArray(new \ArrayIterator(['foo' => 'bar']));
        $this->assertEquals(['foo' => 'bar'], $ar->getArrayCopy());
    }
    public function testExchangeArrayStringArgumentFail()
    {
        $data = ['foo' => 'bar'];
        $this->setExpectedException('InvalidArgumentException');
        $ar = $this->getMockForAbstractClass(AbstractCollection::class, [$data]);
        try {
            $old    = $ar->exchangeArray('Bacon');
        } catch (\Exception $e) {
            // Test data did not change
            $this->assertEquals($data, $ar->getArrayCopy());
            throw $e;
        }
    }
}
