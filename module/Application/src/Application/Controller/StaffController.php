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
use Application\Form\UserForm;
use Application\Model\Account\Staff;
use Application\Form\StaffForm;

class StaffController extends AbstractActionController
{

    protected $staffTable;

    public function getStaffTable()
    {
        if (! $this->staffTable) {
            $this->staffTable = $this->getServiceLocator()->get('Application\Model\Account\StaffTable');
        }
        return $this->staffTable;
    }

    public function indexAction()
    {
        return array();
    }

    public function getStaffListDataAction()
    {
        try {
            $count = $this->getStaffTable()->getFetchAllCounts();
            $logs = $this->getStaffTable()->fetchAll(array(), $_GET['start'], $_GET['length']);

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $logs);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $form = StaffForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        $staff = new Staff();
        $form->bind($staff);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            var_dump($form->isValid());
            if ($form->isValid()) {
                $table = $this->getStaffTable();
                $user = $table->save($staff);
                $this->flashMessenger()->addSuccessMessage($user->staff_name . ' 已添加');
                return $this->redirect()->toUrl('/staff');
            } else {
                print_r($form->getMessages());
            }
        }

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        try {
            $user = $this->getStaffTable()->getOneById($id);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('该员工不存在，请确认后重新操作');
            return $this->redirect()->toUrl('/user');
        }

        $form = UserForm::getInstance($this->getServiceLocator());
        $form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $categoryTable = $this->getCategoryTable();
                $user = $categoryTable->saveCategory($user);
                $this->flashMessenger()->addSuccessMessage($user->title . ' 已编辑');
                return $this->redirect()->toUrl('/category');
            }
        }
        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id', 0);
        $categoryTable = $this->getCategoryTable();
        try {
            $category = $categoryTable->getCategory($id);
            $categoryTable->deleteCategory($id);
            $this->flashMessenger()->addSuccessMessage($category->title . ' 已删除');
            return $this->redirect()->toUrl('/category');
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('该商品类型不存在，请确认后重新操作');
            return $this->redirect()->toUrl('/category');
        }
    }
}
