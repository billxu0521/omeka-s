<?php
namespace Omeka;

use Omeka\Module\AbstractModule;
use Omeka\Mvc\AuthorizationListener;
use Omeka\View\Helper\Api;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * The Omeka module.
 */
class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $serviceManager = $this->getServiceLocator();

        // Inject the API manager into the Api view helper.
        $serviceManager->get('viewhelpermanager')
            ->setFactory('Api', function ($helperPluginManager) use ($serviceManager) {
                return new Api($serviceManager->get('ApiManager'));
            });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
