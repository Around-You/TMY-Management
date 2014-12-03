<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Mobile;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    /**
     *
     * @param \Zend\Mvc\MvcEvent $e
     *            The MvcEvent instance
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach('dispatch', array(
            $this,
            'setLayout'
        ));
    }

    /**
     *
     * @param \Zend\Mvc\MvcEvent $e
     *            The MvcEvent instance
     * @return void
     */
    public function setLayout($e)
    {
        $matches = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        $viewModel = $e->getViewModel();

        if (false === strpos($controller, __NAMESPACE__) || $viewModel->terminate()) {
            // not a controller from this module
            return;
        }
        $viewModel->setTemplate('mobile/layout/layout');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
