<?php

namespace Libadmin\Services\View;;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Strategy\JsonStrategy;

class ViewFormetaStrategyFactory implements FactoryInterface
{
    /**
     * Create and return the JSON view strategy
     *
     * Retrieves the ViewJsonRenderer service from the service locator, and
     * injects it into the constructor for the JSON strategy.
     *
     * It then attaches the strategy to the View service, at a priority of 100.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return JsonStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $formetaRenderer = $serviceLocator->get('FormetaRenderer');
        $formetastrategy = new FormetaStrategy($formetaRenderer);
        return $formetastrategy;
    }
}
