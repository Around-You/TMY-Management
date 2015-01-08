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
            $where = array(
                'staff.enable' => 1
            );
            $count = $this->getStaffTable()->getFetchAllCounts($where);
            $logs = $this->getStaffTable()->fetchAll($where, $_GET['start'], $_GET['length']);

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $logs);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = StaffForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        $staff = new Staff();
        $form->bind($staff);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $table = $this->getStaffTable();
                $staff->password = $staff->encryptPassword();
                $staff = $table->save($staff);
                $this->flashMessenger()->addSuccessMessage($staff->staff_name . ' 已添加');
                return $this->redirect()->toUrl('/staff');
            } else {
                // print_r($form->getMessages());
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
            $staff = $this->getStaffTable()->getOneById($id);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('该员工不存在，请确认后重新操作');
            return $this->redirect()->toUrl('/staff');
        }

        $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = StaffForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        $form->bind($staff);
        if ($request->isPost()) {
            $postData = $request->getPost();
            if($postData['password'] == ''){
                unset($postData['password']);
                unset($postData['confirm_password']);
            }
            $form->setData($postData);
            if ($form->isValid()) {
                $table = $this->getStaffTable();
                if($postData['password'] != ''){
                    $staff->password = $staff->encryptPassword();
                }
                $staff = $table->save($staff);
                $this->flashMessenger()->addSuccessMessage($staff->staff_name . ' 已编辑');
                return $this->redirect()->toUrl('/staff');
            } else {
                // print_r($form->getMessages());
            }
        }

        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id', 0);
        $staffTable = $this->getStaffTable();
        try {
            $staff = $staffTable->getOneById($id);
            $staffTable->deleteById($id);
            $this->flashMessenger()->addSuccessMessage($staff->staff_name . ' 已禁用');
            return $this->redirect()->toUrl('/staff');
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('该员工不存在，请确认后重新操作');
            return $this->redirect()->toUrl('/staff');
        }
    }
}
