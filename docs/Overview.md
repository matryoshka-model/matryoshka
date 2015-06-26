# Overview

> Matryoshka is not an ORM.

Matryoshka is a micro framework (< 1000 SLOC) that helps you to build your model layer in a structured way without the need of using the complex ORM systems, avoiding overheads.
Matryoshka does not provide a persistence layer implementation itself and does not require adapters: you have the full control over the persistence layer by implementing **criterias**.
In order to simplify your job with common persistence systems, a set of wrappers are already provided as separated repositories. They are just a set of ready-to-go classes.
Matryoshka uses a few of Zend Framework 2 components but does not require you to use Zend Framework: you are free to use Matryoshka with any framework.
Last but not least, the Matryoshka design allows you to use just single components or all of them in cooperations. Anyway you can decide how to design your own application model: only you know what your application needs.

## Objects
Matryoshka does not impose you to use a specific kind of domain object nor it requires you to have to extend an abstract class provided by the library. Matryoshka let you choose how your object will be.

You may encounter one of the following situations.

### Array like objects
When you do not need structured objects you can simply use `ArrayObject` as base class for them. In this case, you have to use the [ArrayObjectResultSet](../library/ResultSet/ArrayObjectResultSet.php) class.

### Objects with public properties
If you want to use something like:
```php
class MyDomainObject
{
   public $id;
   public $foo;
}
```
you have to use the [HydratingResultSet](../library/ResultSet/HydratingResultSet.php) class with the [ObjectProperty](https://github.com/zendframework/zend-stdlib/blob/master/src/Hydrator/ObjectProperty.php) hydrator.

### Structured objects
When you want to use full structured objects with setter/getter methods, you have to use the [HydratingResultSet](../library/ResultSet/HydratingResultSet.php) class with the [ClassMethods](../library/Hydrator/ClassMethods.php) hydrator.
We highly recommend to use this solution, better if in combination with a well defined interface.

### Other features
Optionally you can add one or more of the following interfaces to your object classes and Matryoshka will use them to automatise some processes:
- [HydratingAwareInterface](https://github.com/zendframework/zend-stdlib/blob/master/src/Hydrator/HydratorAwareInterface.php) allows object to define its own hydrator, Matryoshka will use it when needed.
- [InputFilterAwareInterface](https://github.com/zendframework/zend-inputfilter/blob/master/src/InputFilterAwareInterface.php) allows object to define its own input filter, Matryoshka will use it when needed.
- [ModelAwareInterface](../library/ModelAwareInterface.php) allows object to use its own model class, Matryoshka will inject its instance.
- [ActiveRecordInterface](../library/Object/ActiveRecord/ActiveRecordInterface.php) adds the ability to save/delete object by using the object instance itself.

Furthermore, you can also use the [ObjectManager](../library/Object/ObjectManager.php) to get your object instances. It is a dedicated [service locator](https://github.com/zendframework/zend-servicemanager/blob/master/src/ServiceLocatorInterface.php) that allows you to register and to factory your objects. The default [abstract factory](../library/Object/Service/ObjectAbstractServiceFactory.php) will also inject dependencies of your objects.


