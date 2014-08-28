### Fetch and Hydrate objects from a Mongo Collection

This is the extended way to fetch and Hydrate objects using a Mongo Collection as Data Gateway.

**Assume to have a Person object**

```php

class Place
{
    public $name;

    public function getName()
    {
        return $this->name;
    }
}

```

**Create an instance of a MongoDb Collection (your Data Gateway)**

```php

$mongo = new \MongoClient();
$db = $mongo->selectDB('exampleDb');
$collection = $db->selectCollection('places');

```

**Create the model hydrating each result with the Place object.**

```php
$hydrator = new \Zend\Stdlib\Hydrator\ObjectProperty();
$resultSetPrototype = new \Matryoshka\Model\ResultSet\HydratingResultSet($hydrator);
$resultSetPrototype->setObjectPrototype(new Place());

$model = new \Matryoshka\Model\Model($collection, $resultSetPrototype);
```

**Find all places using the callable criteria**

```php
$places = $model->find(
    new \Matryoshka\Model\Criteria\CallableCriteria(
        function ($model) {
            $dataGateway = $model->getDataGateway();
            return $dataGateway->find()->limit(100);
        }
    )
);
```

**Do whatever you want with the ResultSet**

```php
foreach ($places as $place) {
    echo $place->getName() . PHP_EOL
}
```

