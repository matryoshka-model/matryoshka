#Configuration

In order to work with Matryoshka you need to handle service instances. You can simply obtain configured services by using the built-in factories via the `ServiceManager`. If you use Zend Framework then the [integration module](https://github.com/matryoshka-model/zf2-matryoshka-module) will do the job for you, otherwise you can setup the `ServiceManager` like the following example:

```php
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

$serviceManager = new ServiceManager(new Config([
    'service_manager' => [
        'factories' => [
            'Matryoshka\Model\ModelManager' => 'Matryoshka\Model\Service\ModelManagerFactory',
            'Matryoshka\Model\Object\ObjectManager' => 'Matryoshka\Model\Object\Service\ObjectManagerFactory',
            'Matryoshka\Model\Object\PrototypeStrategy\ServiceLocatorStrategy'
            => 'Matryoshka\Model\Object\PrototypeStrategy\Service\ServiceLocatorStrategyFactory',
        ],
        'invokables' => [
            'Matryoshka\Model\ResultSet\ArrayObjectResultSet' => 'Matryoshka\Model\ResultSet\ArrayObjectResultSet',
            'Matryoshka\Model\ResultSet\HydratingResultSet' => 'Matryoshka\Model\ResultSet\HydratingResultSet',
        ],
        'shared' => [
            'Matryoshka\Model\ModelManager' => true,
            'Matryoshka\Model\Object\ObjectManager' => true,
            'Matryoshka\Model\ResultSet\ArrayObjectResultSet' => false,
            'Matryoshka\Model\ResultSet\HydratingResultSet' => false,
        ],
    ],
]));
```

Furthermore, each service requires its configuration as explained below.
In this documentation we are assuming that any other configurations will be added to the `$config` array:

```php
$config = [
     // Services configurations here
];

$serviceManager->setService('Config', $config);
```

Finally, remember that any additional **service** used by configurations must be registered in the `ServiceManager` or in a dedicated manager. [Here](http://framework.zend.com/manual/current/en/modules/zend.service-manager.quick-start.html) a better explanation how to configure a manager.

## Objects

Using the [object abstract factory](../library/Object/Service/ObjectAbstractServiceFactory.php) (enabled by default) you can register and configure your [objects](Overview.md#objects) in the [object manager](../library/Object/ObjectManager.php):

```php
'matryoshka-objects' => [ // Object abstract service factory
        
       'YourObjectName' => [
            'type'              => '', // object class name that will be factored

            // Optionally:
            'hydrator'          => '', // hydrator service name
            'input_filter'      => '', // input filter service name

            // Only when using Active Record
            'active_record_criteria' => '', // active record criteria service name
        ],

        // other objects here...
],
```

Also you can add any other [manager configuration](http://framework.zend.com/manual/current/en/modules/zend.service-manager.quick-start.html#using-configuration) for the [object manager](../library/Object/ObjectManager.php) here:

```php
'matryoshka' => [
        'object_manager' => [
             // object manager configuration here
        ],
    ],
```

### Prototype strategies
For special cases, Matryoshka provides a [service locator based prototype strategy](../library/Object/PrototypeStrategy/ServiceLocatorStrategy.php) for object creation. This particular strategy allows other components (i.e. the [HydratingResultSet](../library/ResultSet/HydratingResultSet.php)) to use a `ServiceLocator` (i.e. the object manager) in order to create objects.
This strategy has the following configuration:

```php
 'matryoshka-object-servicelocatorstrategy' => [ 
        // all fields are optional!
        'service_locator'   => '', // the service locator, default to 'Matryoshka\Model\Object\ObjectManager',
        'type_field'        => '', // a context field name used to choose which type of object to create
        'validate_object'   => true, // if true, it will be checked if the created object is an instance of the object prototype. Default to true.
        'clone_object'      => false, // create the object by cloning the object prototype. Default to false, because the ObjectManager clones the object already.
    ],
```


## Models
A [model service](Overview.md#models) requires at least a **datagateway**, an **object prototype**, and a **resultset protype**. 
Datagateway services have to be registered in the `ServiceManager` depending the persistence layer you use. Wrappers provide factories and additional configurations for datagateways.

Model service configuration handled by the default [model abstract factory](../library/Service/ModelAbstractServiceFactory.php):

```php
'matryoshka-models' => [
    'YourModelName' => [
            'datagateway'        => '', // datagateway service name
            'object'             => 'YourObjectName', // object service name for the object prototype
            'resultset'          => '', // resultset service name for the resultset prototype
            // Optionally
            'prototype_strategy' => '', // object prototype strategy service name
            'buffered_resultset' => true, // if true the resultset will be composed by Matryoshka\Model\ResultSet\BufferedResultSet, false by default
            'type'               => '', // model class name that will be factored, if not specified the Matryoshka\Model\Model class will be used
            'paginator_criteria' => '', // paginator criteria service name
            'hydrator'           => '', // hydrator service name
            'input_filter'       => '', // hydrator service name
    ],
    
    // other models here...
],
```

Matryoshka also provides an [event-driven extension of the Model class](../library/ObservableModel.php) that can be used by including the following configuration:

```php
'YourModelName' => [
           // ...other config as above...
           'type'      => 'Matryoshka\Model\Model\ObservableModel',
           'listeners' => [
               // a list of listeners service names that implements Zend\EventManager\ListenerAggregateInterface
              'YourModelListenerOne',
              'YourModelListenerTwo',
              // ...
           ]
        ],
```

Note that for the `type` field you can use any full qualified name of a class implementing the [ModelInterface](../library/ModelInterface.php).

Also you can add any other [manager configuration](http://framework.zend.com/manual/current/en/modules/zend.service-manager.quick-start.html#using-configuration) for the [model manager](../library/ModelManager.php) here:

```php
'matryoshka' => [
        'model_manager' => [
             // model manager configuration here
        ],
    ],
```

## Criterias
There're no special configurations for [criteria](Overview.md#criterias) because you can instantiate criteria classes directly when needed (as we suggest). Just remember that when you use criterias in a configuration you need to register them in the `ServiceManager` as explained above.

## Other components
Generally if a dedicated manager is not available Matryoshka will try to get service instances by using the global `ServiceManager`. 
For hydrators and inputfilters, even if dedicated managers are not in bundle, Matryoshka will try to use the dedicated managers if available (`HydratorManager` and `InputFilterManager` respectively). Usually, the MVC setup of Zend Framework provides them. 
