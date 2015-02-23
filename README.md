# Matryoshka 

[![Latest Stable Version](http://img.shields.io/packagist/v/matryoshka-model/matryoshka.svg?style=flat-square)](https://packagist.org/packages/matryoshka-model/matryoshka) [![Total Downloads](https://img.shields.io/packagist/dt/matryoshka-model/matryoshka.svg?style=flat-square)](https://packagist.org/packages/matryoshka-model/matryoshka)

| Master  | Develop |
|:-------------:|:-------------:|
| [![Build Status](https://img.shields.io/travis/matryoshka-model/matryoshka/master.svg?style=flat-square)](https://travis-ci.org/matryoshka-model/matryoshka) | [![Build Status](https://img.shields.io/travis/matryoshka-model/matryoshka/develop.svg?style=flat-square)](https://travis-ci.org/matryoshka-model/matryoshka)  |
| [![Coveralls branch](https://img.shields.io/coveralls/matryoshka-model/matryoshka/master.svg?style=flat-square)](https://coveralls.io/r/matryoshka-model/matryoshka?branch=master) | [![Coveralls branch](https://img.shields.io/coveralls/matryoshka-model/matryoshka/develop.svg?style=flat-square)](https://coveralls.io/r/matryoshka-model/matryoshka?branch=develop) |

Matryoshka is a lightweight framework that provides a standard and easy way to implement a model [service layer](http://martinfowler.com/eaaCatalog/serviceLayer.html). 
Its layered design aims to provide a strong seperation between the persistence and the rest of your application, whether the datagateway you need to use (i.e. Zend\Db, MongoCollection, an ORM, a REST client or anything else).
In order to work with Matryoshka, developers need just to setup services using its comprehensive configuration system and implement very simple [criteria](http://en.wikipedia.org/wiki/Criteria_Pattern) interfaces.
Furthermore, a set of [wrapper](http://en.wikipedia.org/wiki/Wrapper_library) for common persistence systems are provided as separeted libraries. 

## Theory of operation

Matryoshka doesn't provide a persistence layer implementation itself, but it can work with any third party implementation that acts as datagateway. Regardless of the datagateway you choose, Matryoshka provides to clients (i.e. your controller) the same set of handful API. To accomplish this goal Matryoshka uses criteria interfaces that developer have to implement. 

Think a criteria as a small piece of code that tell to the datagateway how to perform an operation on your dataset: for example filter some rows. So, each criteria represent a simple task: different kind of criteria interfaces are defined for write, read and delete operations. Also, in concrete criteria classes, the developer can add methods to augment the "query interface" with domain specific logic.

A concrete criteria class acts both as a criterion defining a query interface and also as [mediator](http://en.wikipedia.org/wiki/Mediator_pattern) between model layer and datagateway. Only criteria classes performs operations against the datagateway interface, instead Matryoshka's components do not.

So Matryoska is unawareness about the datagateway interface, that makes it working with any kind of third-party datagateway implementation.

Matryoshka dolls (layers):

* ModelManager
    A dedicated service locator for your model service classes (i.e., model)

* Model
    A service class representing a collection of entities that provides common features in a centralized way (e.g., CRUD, resultset, paginating, hydrating, input filtering)

* Criteria
    An "user query intefarce" from an API point of view, also acting as mediator between model and datagateway

* Datagateway
    Any kind of datagateway, like `Zend\Db\TableGateway` or `\MongoCollection`

Basic usage example:

```php
//Assiming MyModel an instance of a Matryoshka Model class registered in Matryoshka model manager
$myModel = $modelManager->get('MyModel');

//Assuming MyCriteria a class extending ReadableCriteriaInterface
$criteria = new MyCriteria(); 
$criteria->setMyCustomFilter('foo');

//Execute a query
$resultSet = $myModel->find($criteria);
```

## Installation

Install it using [composer](http://getcomposer.org).

Add the following to your `composer.json` file:

```
"require": {
    "php": ">=5.4",
    "matryoshka-model/matryoshka": "~0.6.0",
}
```

---

[![Analytics](https://ga-beacon.appspot.com/UA-49655829-1/matryoshka-model/matryoshka)](https://github.com/igrigorik/ga-beacon)

