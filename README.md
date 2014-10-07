# Matryoshka [![Latest Stable Version](https://poser.pugx.org/matryoshka-model/matryoshka/v/stable.png)](https://packagist.org/packages/matryoshka-model/matryoshka)&nbsp;[![Dependency Status](https://www.versioneye.com/user/projects/5433b5fc84981fb8820000df/badge.svg)](https://www.versioneye.com/user/projects/5433b5fc84981fb8820000df)&nbsp;[![Total Downloads](https://poser.pugx.org/matryoshka-model/matryoshka/downloads.svg)](https://packagist.org/packages/matryoshka-model/matryoshka)

| Master  | Develop |
|:-------------:|:-------------:|
| [![Build Status](https://secure.travis-ci.org/matryoshka-model/matryoshka.svg?branch=master)](https://travis-ci.org/matryoshka-model/matryoshka)  | [![Build Status](https://secure.travis-ci.org/matryoshka-model/matryoshka.svg?branch=develop)](https://travis-ci.org/matryoshka-model/matryoshka)  |
| [![Coverage Status](https://coveralls.io/repos/matryoshka-model/matryoshka/badge.png?branch=master)](https://coveralls.io/r/matryoshka-model/matryoshka)  | [![Coverage Status](https://coveralls.io/repos/matryoshka-model/matryoshka/badge.png?branch=develop)](https://coveralls.io/r/matryoshka-model/matryoshka)  |

Matryoshka is a model [service layer](http://martinfowler.com/eaaCatalog/serviceLayer.html) that normalizes and standardizes your model's interface use, whether you are using Zend\Db, Mongo, Doctrine or anything else.

## Theory of operation

Matryoshka provides an handful API based on **criteria** interfaces. Think about criterias as a set of small objects.

The developer have to implement them: each criteria encapsulates a small piece of business logic and exposes a small interface.

Criterias use the datagateway, instead Matryoshka's components do not use datagateway directly, so any kind of datagateway can be used.

Main layers:

* ModelManager
    A dedicated service locator for your model service classes (i.e., model)

* Model
    A service class representing a collection of entities that provides common features in a centralized way (e.g., CRUD, result set, paginating, hydrating, input filtering)

* Criteria
    An "user query intefarce" from an API point of view, acting as mediator between model and datagateway

* Datagateway
    Any kind of datagateway, like `Zend\Db\TableGateway` or `\MongoCollection`


## Installation

Install it using [composer](http://getcomposer.org).

Add the following to your `composer.json` file:

```
"require": {
    "php": ">=5.4",
    "matryoshka-model/matryoshka": "~0.5.0",
}
```

---

[![Analytics](https://ga-beacon.appspot.com/UA-49655829-1/matryoshka-model/matryoshka)](https://github.com/igrigorik/ga-beacon)

