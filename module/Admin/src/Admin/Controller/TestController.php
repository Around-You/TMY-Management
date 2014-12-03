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
use Zend\View\Model\JsonModel;

class TestController extends AbstractActionController
{

    public function removeTestDataAction()
    {
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $statement = $db->query('delete from product where user_id=1');
        $statement->execute();
//         $statement = $db->query('delete from product_image where user_id=1');
//         $statement->execute();
        $statement = $db->query('delete from category where user_id=1');
        $statement->execute();
        return new JsonModel();
    }
}
