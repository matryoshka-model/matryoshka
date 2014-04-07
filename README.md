# Matryoshka [![Build Status](https://travis-ci.org/ripaclub/matryoshka.svg)](https://travis-ci.org/ripaclub/matryoshka)

[m&#592;'tr<sup>j</sup>&#629;&#642;k&#601;]

Matryoshka is a Model Service Layer that normalize and standardize your model's interface use,
whether you are using Zend\Db, Mongo, Doctrine or anything else.

Matryoshka provides an handful API based on **criterias**, think about criterias as if they are a "user query interface" that's more abstract than the datagateway one.

## Installation

Since a Matryoshka stable version has not been released yet you have to put
the Matryoshka repository in your composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ripaclub/matryoshka.git"
        }
    ]
```

And of course you have to add it to your dependencies.

```
"ripaclub/matryoshka" : "dev-develop"
```

[![Analytics](https://ga-beacon.appspot.com/UA-49655829-1/ripaclub/matryoshka)](https://github.com/igrigorik/ga-beacon)

