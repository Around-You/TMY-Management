<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Application;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
//         $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
//             $this,
//             'setLayout'
//         ));
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array(
            $this,
            'onCatchApplicationException'
        ), -100);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array(
            $this,
            'onCatchApplicationException'
        ), -100);
        $eventManager->attach(MvcEvent::EVENT_RENDER, array(
            $this,
            'registerJsonStrategy'
        ), 100);
    }

    /**
     *
     * @param \Zend\Mvc\MvcEvent $e
     *            The MvcEvent instance
     * @return void
     */
//     public function setLayout($e)
//     {
//         $matches = $e->getRouteMatch();
//         $controller = $matches->getParam('controller');
//         $viewModel = $e->getViewModel();

//         $oldTemplate = $viewModel->getTemplate();

//         if (false === strpos($controller, __NAMESPACE__) || $viewModel->terminate() || strpos($oldTemplate, 'admin') === 0) {
//             // not a controller from this module
//             return;
//         }

//         $viewModel->setTemplate('admin/layout/layout');
//     }

    /**
     *
     * @param \Zend\Mvc\MvcEvent $e
     *            The MvcEvent instance
     * @return void
     */
    public function registerJsonStrategy(MvcEvent $e)
    {
        $matches = $e->getRouteMatch();
        if (! $matches) {
            return;
        }
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__)) {
            // not a controller from this module
            return;
        }

        // Potentially, you could be even more selective at this point, and test
        // for specific controller classes, and even specific actions or request
        // methods.

        // Set the JSON strategy when controllers from this module are selected
        $app = $e->getTarget();
        $locator = $app->getServiceManager();
        $view = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('ViewJsonStrategy');

        // Attach strategy, which is a listener aggregate, at high priority
        $view->getEventManager()->attach($jsonStrategy, 100);
    }

    public function onCatchApplicationException(MvcEvent $e)
    {

        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error) || $error != Application::ERROR_EXCEPTION) {
            return;
        }

        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }

        $exception = $e->getParam('exception');
        $response = $e->getResponse();
        switch ($exception->getCode())
        {
        	case 403:
        	    $response->getHeaders()->addHeaderLine('Location', '/account/login');
        	    $response->setStatusCode(302);
        	    break;
        }


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

    public function getServiceConfig()
    {
        return array();
    }
}
