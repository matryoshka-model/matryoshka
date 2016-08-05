<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Service;

use Interop\Container\ContainerInterface;
use Matryoshka\Model\ModelManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ModelManagerFactory
 */
class ModelManagerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $modelConfig = [];
        if (isset($config['matryoshka']) && isset($config['matryoshka']['model_manager'])) {
            $modelConfig = $config['matryoshka']['model_manager'];
        }

        $modelManager = new ModelManager($container, $modelConfig);
        $modelManager->addAbstractFactory(new ModelAbstractServiceFactory());
        return $modelManager;
    }
}
