<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use SamFramework\Core\App;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $user = App::getUser();
        if ($user) {
            $this->redirect()->toUrl('/admin/product');
        }else{
            $this->redirect()->toUrl('/admin/user/login');
        }
    }
}
