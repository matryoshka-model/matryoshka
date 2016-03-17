<p><img align="right" src="./docs/assets/images/matryoshka_logo_hi_res_512.png" width="64px" height="64px"/></p>
<p></p>
Matryoshka
----------

[![Latest Stable Version](http://img.shields.io/packagist/v/matryoshka-model/matryoshka.svg?style=flat-square)](https://packagist.org/packages/matryoshka-model/matryoshka) [![Build Status](https://img.shields.io/travis/matryoshka-model/matryoshka/master.svg?style=flat-square)](https://travis-ci.org/matryoshka-model/matryoshka) [![Coveralls branch](https://img.shields.io/coveralls/matryoshka-model/matryoshka/master.svg?style=flat-square)](https://coveralls.io/r/matryoshka-model/matryoshka?branch=master) [![Total Downloads](https://img.shields.io/packagist/dt/matryoshka-model/matryoshka.svg?style=flat-square)](https://packagist.org/packages/matryoshka-model/matryoshka) [![Matryoshka Model's Slack](http://matryoshka-slackin.herokuapp.com/badge.svg?style=flat-square)](http://matryoshka-slackin.herokuapp.com)

> Matryoshka is not an ORM.

Matryoshka is a micro framework (< 1000 SLOC) that helps you to build your model [service layer](http://martinfowler.com/eaaCatalog/serviceLayer.html) in a structured way without the need of using the complex ORM systems, avoiding overheads.
Matryoshka does not provide a persistence layer implementation itself and does not require adapters: you have the full control over the persistence layer by implementing [criterias](http://en.wikipedia.org/wiki/Criteria_Pattern).
Its layered design aims to provide a strong seperation between the persistence and the rest of your application, whether the datagateway you need to use (i.e. Zend\Db, MongoCollection, a REST client or anything else).
In order to simplify your job with common persistence systems, a set of [wrappers](http://en.wikipedia.org/wiki/Wrapper_library) are already provided as separated repositories. They are just a set of ready-to-use classes.
Matryoshka uses a few of [Zend Framework 2](http://framework.zend.com/) components but does not require you to use Zend Framework: you are free to use Matryoshka with any framework.
Last but not least, the Matryoshka design allows you to use just single components or all of them in cooperations. Anyway you can decide how to design your own application model: only you know what your application needs.

[Read more about Matryoshka components](docs/Overview.md)

#### Wrappers:
- [rest-wrapper](https://github.com/matryoshka-model/rest-wrapper)
- [mongo-wrapper](https://github.com/matryoshka-model/mongo-wrapper)

#### Integration modules:
- [zf2-matryoshka-module](https://github.com/matryoshka-model/zf2-matryoshka-module)
- [zf-apigility-matryoshka](https://github.com/matryoshka-model/zf-apigility-matryoshka)

#### Others:

Other addons, plugins, and modules made with Matryoshka.

- [mongo-transactional](https://github.com/matryoshka-model/mongo-transactional)

#### Community

For questions and support please visit the [slack channel](http://matryoshka.slack.com) (get an invite [here](http://matryoshka-slackin.herokuapp.com)).

## Theory of operation

Matryoshka doesn't provide a persistence layer implementation itself, but it can work with any third party implementation that acts as datagateway. Regardless of the datagateway you choose, Matryoshka provides to clients (i.e. your controller) the same set of handful API. To accomplish this goal Matryoshka uses criteria interfaces that developer have to implement.

Think a criteria as a small piece of code that tell to the datagateway how to perform an operation on your dataset: for example filter some rows. So, each criteria represent a simple task: different kind of criteria interfaces are defined for write, read and delete operations. Also, in concrete criteria classes, the developer can add methods to augment the "query interface" with domain specific logic.

A concrete criteria class acts both as a criterion defining a query interface and also as [mediator](http://en.wikipedia.org/wiki/Mediator_pattern) between model layer and datagateway. Only criteria classes performs operations against the datagateway interface, instead Matryoshka's components do not.

So Matryoska is unawareness about the datagateway interface, that makes it working with any kind of third-party datagateway implementation.

Matryoshka dolls (layers):

* **ModelManager**
    A dedicated service locator for your model service classes (i.e., model)

* **Model**
    An end user service that manages a collection of related data by using criterias (similar to a table gateway or a document collection)

* **Criteria**
    An "user query interface" from an API point of view, also acting as mediator between model and datagateway

Finally, in the empty space of the innermost doll you can put:

* **Datagateway**
    Any kind of datagateway, like `Zend\Db\TableGateway` or `\MongoCollection` or a REST client

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
    "matryoshka-model/matryoshka": "~0.8.0"
}
```

## Requirements

- PHP >= 5.5

## Configuration

[Read about Matryoshka configuration here](docs/Configuration.md)


---

[![Analytics](https://ga-beacon.appspot.com/UA-49657176-2/matryoshka?flat)](https://github.com/igrigorik/ga-beacon)

