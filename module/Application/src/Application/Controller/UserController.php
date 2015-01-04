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
use Zend\View\Model\JsonModel;
use Application\Model\Json\DataTableResult;

class UserController extends AbstractActionController
{

    protected $userTable;

    public function getUserTable()
    {
        if (! $this->userTable) {
            $this->userTable = $this->getServiceLocator()->get('Application\Model\Account\UserTable');
        }
        return $this->userTable;
    }

    public function indexAction()
    {
        return array();
    }

    public function getUserListDataAction()
    {
        try {
            $count = $this->getUserTable()->getFetchAllCounts();
            $logs = $this->getUserTable()->fetchAll(array(), $_GET['start'], $_GET['length']);

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $logs);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }
}
