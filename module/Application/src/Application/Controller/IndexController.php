<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use SamFramework\Core\App;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $this->layout('layout/layout/withoutSideBar');
        if(App::isGuest()){
            $this->redirect()->toUrl('/account/login');
        }else{
            $this->redirect()->toUrl('/dashboard');
        }
    }

}
