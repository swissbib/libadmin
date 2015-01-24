<?php

namespace Libadmin\Services\View;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewFormetaRendererFactory implements FactoryInterface
{
    /**
     * Create and return the Formeta view renderer
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return FormetaRenderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $formetaRenderer = new FormetaRenderer();
        return $formetaRenderer;
    }
}
