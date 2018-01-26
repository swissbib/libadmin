<?php

namespace Libadmin\Services\View;;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Strategy\JsonStrategy;

class ViewFormetaStrategyFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formetaRenderer = $container->get('FormetaRenderer');
        $formetastrategy = new FormetaStrategy($formetaRenderer);
            return $formetastrategy;
    }
}
