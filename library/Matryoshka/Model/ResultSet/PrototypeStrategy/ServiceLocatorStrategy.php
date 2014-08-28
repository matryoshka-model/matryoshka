<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\ResultSet\PrototypeStrategy;

use Zend\ServiceManager\ServiceLocatorInterface;
use Matryoshka\Model\Exception\ErrorException;
class ServiceLocatorStrategy implements PrototypeStrategyInterface
{

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var string
     */
    protected $typeField = 'type';

    /**
     * @var bool
     */
    protected $validateObject = true;

    /**
     * @var bool
     */
    protected $cloneObject = false;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $typeField
     * @param string $validateObject
     * @param string $cloneObject
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, $typeField = 'type', $validateObject = true, $cloneObject = false)
    {
        $this->setServiceLocator($serviceLocator);
        $this->setTypeField($typeField);
        $this->setValidateObject($validateObject);
        $this->setCloneObject($cloneObject);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ServiceLocatorStrategy
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param string $typeField
     * @return ServiceLocatorStrategy
     */
    public function setTypeField($typeField)
    {
        $this->typeField = (string) $typeField;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeField()
    {
        return $this->typeField;
    }

    /**
     * @param bool $validateObject
     * @return ServiceLocatorStrategy
     */
    public function setValidateObject($validateObject)
    {
        $this->validateObject = (bool) $validateObject;
        return $this;
    }

    /**
     * @return bool
     */
    public function getValidateObject()
    {
        return $this->validateObject;
    }

    /**
     * @param bool $cloneObject
     * @return ServiceLocatorStrategy
     */
    public function setCloneObject($cloneObject)
    {
        $this->cloneObject = (bool) $cloneObject;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCloneObject()
    {
        return $this->cloneObject;
    }

    /**
     * @param object $objectPrototype
     * @param array $context
     * @return object
     */
    public function createObject($objectPrototype, array $context = null)
    {
        $object = $this->serviceLocator->get($context[$this->typeField]);

        if ($this->validateObject && !($object instanceof $objectPrototype)) {
            throw new ErrorException('Object must be an instance of $objectPrototype');
        }

        if ($this->cloneObject) {
            $object = clone $object;
        }

        return $object;
    }

}