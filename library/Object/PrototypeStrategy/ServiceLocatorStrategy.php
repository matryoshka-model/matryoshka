<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2015, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object\PrototypeStrategy;

use Matryoshka\Model\Exception\ErrorException;
use Matryoshka\Model\Exception\RuntimeException;
use Matryoshka\Model\ModelAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceLocatorStrategy
 *
 * Strategy for the creation of objects using an exteral service locator.
 * It also allows the creation of different types of similar objects based on context.
 */
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
     * @param bool|string $validateObject
     * @param bool|string $cloneObject
     */
    public function __construct(
        ServiceLocatorInterface $serviceLocator,
        $typeField = 'type',
        $validateObject = true,
        $cloneObject = false
    ) {
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
     * @return array|object
     * @throws ErrorException
     */
    public function createObject($objectPrototype, array $context = null)
    {
        if (!isset($context[$this->typeField])) {
            throw new RuntimeException(sprintf(
                '"%s" is not present within object data',
                $this->typeField
            ));
        }

        $object = $this->serviceLocator->get($context[$this->typeField]);

        if ($this->validateObject && !($object instanceof $objectPrototype)) {
            throw new ErrorException(sprintf(
                'Object must be an instance of %s',
                get_class($objectPrototype)
            ));
        }

        if ($this->cloneObject) {
            $object = clone $object;
        }

        if ($objectPrototype instanceof ModelAwareInterface && $objectPrototype->getModel()
            && $object instanceof ModelAwareInterface
        ) {
            $object->setModel($objectPrototype->getModel());
        }

        return $object;
    }
}
