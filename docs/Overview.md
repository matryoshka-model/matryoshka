# Overview

- [Objects](Overview.md#Objects)
- [Models](Overview.md#Models)
- [Criterias](Overview.md#Criterias)

## Objects
Matryoshka does not impose you to use a specific kind of domain object nor it requires you to have to extend an abstract class provided by the library. Matryoshka let you choose how your object will be.

You may encounter one of the following situations.

#### Array like objects
When you do not need structured objects you can simply use `ArrayObject` as base class for them. In this case, you have to use the [ArrayObjectResultSet](../library/ResultSet/ArrayObjectResultSet.php) class.

#### Objects with public properties
If you want to use something like:
```php
class MyDomainObject
{
   public $id;
   public $foo;
}
```
you have to use the [HydratingResultSet](../library/ResultSet/HydratingResultSet.php) class with the [ObjectProperty](https://github.com/zendframework/zend-stdlib/blob/master/src/Hydrator/ObjectProperty.php) hydrator.

#### Structured objects
When you want to use full structured objects with setter/getter methods, you have to use the [HydratingResultSet](../library/ResultSet/HydratingResultSet.php) class with the [ClassMethods](../library/Hydrator/ClassMethods.php) hydrator.
We highly recommend to use this solution, better if in combination with a well defined interface.

### Other features
Optionally you can add one or more of the following interfaces to your object classes and Matryoshka will use them to automatise some processes:
- [HydratingAwareInterface](https://github.com/zendframework/zend-stdlib/blob/master/src/Hydrator/HydratorAwareInterface.php) allows object to define its own hydrator, Matryoshka will use it when needed.
- [InputFilterAwareInterface](https://github.com/zendframework/zend-inputfilter/blob/master/src/InputFilterAwareInterface.php) allows object to define its own input filter, Matryoshka will use it when needed.
- [ModelAwareInterface](../library/ModelAwareInterface.php) allows object to use its own model class, Matryoshka will inject its instance.
- [ActiveRecordInterface](../library/Object/ActiveRecord/ActiveRecordInterface.php) adds the ability to save/delete object by using the object instance itself.

Furthermore, you can also use the [ObjectManager](../library/Object/ObjectManager.php) to get your object instances. It is a dedicated [service locator](https://github.com/zendframework/zend-servicemanager/blob/master/src/ServiceLocatorInterface.php) that allows you to register and to factory your objects. The default [abstract factory](../library/Object/Service/ObjectAbstractServiceFactory.php) will also inject dependencies of your objects. Configurations [here](Configuration.md#objects).

## Models

### How it works
The core of Matryoshka is the model service concept: a class implementing both the [ModelInterface](../library/ModelInterface.php) (the contract for any end user service that manages a collection of related data providing the same set of handful API to clients) and the [ModelStubInterface](../library/ModelStubInterface.php) (the contract for model stub objects that allow access to persistence related services such as the datagateway).

So, Matryoshka requires you implement concrete [criterias](Criterias) (those strictly related to your application business logic) which act on the layer of persistence through the use of the [ModelStubInterface](../library/ModelStubInterface.php). The model service (that implements  [ModelStubInterface](../library/ModelStubInterface.php)) passes its instance to the criteria objects when used with them.

On the other hand, when you use a model service in your application, you can pass [criterias](Criterias) objects to the model service (that implements [ModelInterface](../library/ModelInterface.php)) in order to perform an operation on the data.

### How to use a model service
**[WIP]**

### Default model class

Matryoshka provides a ready-to-use implementation of model service: the [Model](../library/Model.php) class. It can be used just [configuring the model manager](Configuration.md#models). 

Furthermore, you can extend the [Model](../library/Model.php) class adding your own business logics and customisations. Also we suggest to extend it always with a simple placeholder class just for typing purpose:

```php
use Matryoshka\Model\Model;

final class MyModel extend Model 
{
}
```

### Event-driven Model class

Another way to extend the behaviour of a model service is by using the [ObservableModel](../library/ObservableModel.php): an event-driven extension of the [Model](../library/Model.php) class.

This class allow you to attach listeners in order to observe or change the model behaviour without having to extend the base [Model](../library/Model.php) class.

It is implemented by composing the [EventManager](http://framework.zend.com/manual/current/en/modules/zend.event-manager.event-manager.html). It defines a set of pre/post events for each actions performed on the model, as defined inside the specialised [ModelEvent](../library/ModelEvent.php) class that represents the event, encapsulates the target context and parameters passed, and provides some behaviour for interacting with the event manager.

The [ObservableModel](../library/ObservableModel.php) can be easily enabled and listeners can be attached by [configuring the model manager](Configuration.md#models).  

### Other features
- Object and resultset prototypes **[WIP]**
- HydratorAwareInterface **[WIP]**
- InputFilterAwareInterface **[WIP]**

## Criterias
**[WIP]**
