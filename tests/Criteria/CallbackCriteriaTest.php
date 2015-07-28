<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Leonardo Di Donato <leodidonato at gmail dot com>, Leonardo Grasso <me at leonardograsso dot com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Criteria;

use Matryoshka\Model\Criteria\CallbackCriteria;

/**
 * Class CallbackCriteriaTest
 */
class CallbackCriteriaTest extends \PHPUnit_Framework_TestCase
{

    protected $closure;

    protected $criteria;

    public function setUp()
    {
        $this->closure = function () {
        };
        $this->criteria = new CallbackCriteria($this->closure);
    }

    public function testCtor()
    {
        $this->assertInstanceOf('Matryoshka\Model\Criteria\AbstractCriteria', $this->criteria);
        $this->assertInstanceOf('Matryoshka\Model\Criteria\ReadableCriteriaInterface', $this->criteria);

        $refl = new \ReflectionClass($this->criteria);
        $reflProp = $refl->getProperty('callback');
        $reflProp->setAccessible(true);
        $this->assertSame($this->closure, $reflProp->getValue($this->criteria));
    }

    protected $modelMock;

    protected $callbackReturn;

    public function callbackFunction($model)
    {
        $this->assertSame($this->modelMock, $model);
        return $this->callbackReturn;
    }

    public function testApply()
    {
        $this->modelMock = $modelMock = $mockCriteria = $this->getMock(
            '\Matryoshka\Model\ModelStubInterface'
        );

        $this->callbackReturn = ['foo' => 'bar'];

        // Test non-closure callable
        $criteria = new CallbackCriteria([$this, 'callbackFunction']);
        $this->assertSame($this->callbackReturn, $criteria->apply($modelMock));


        // Test closure
        $argv = null;
        $scope = null;
        $return = ['return'];

        $criteria = new CallbackCriteria(function () use (&$argv, &$scope, $return) {
            $argv  = func_get_args();
            $scope = $this;
            return $return;
        });


        $this->assertSame($return, $criteria->apply($modelMock));
        $this->assertSame($criteria, $scope);
        $this->assertSame([0 => $modelMock], $argv);
    }
}
