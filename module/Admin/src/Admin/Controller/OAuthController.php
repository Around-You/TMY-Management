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


class OAuthController extends AbstractActionController
{

    public function loginAction()
    {
        $channel = $this->params('channel', '');

        if (!isset($_GET['code'])) {
        	return $this->notFoundAction();
        }
        $code = $_GET['code'];
        echo $code;
    }
}
