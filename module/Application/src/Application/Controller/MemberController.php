<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use SamFramework\Core\App;
use Zend\View\Model\JsonModel;
use Application\Form\MemberForm;
use Application\Model\Goods\Goods;
use Application\Model\Member\Member;
use Application\Model\Goods\MemberGoods;
use Application\Model\Json\DataTableResult;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Expression;
use Application\Form\MemberGoodsForm;
use Application\Model\Json\JsonResult;
use Application\Model\Logs\MemberLog;

class MemberController extends AbstractActionController
{

    public function getGoodsForMemberForm()
    {
        $resultSet = $this->getGoodsTable()->getAllEnableVirtualGoods();
        return $this->getGoodsTable()->formatGoodsResultSetToSelect($resultSet);
    }

    public function getStaffForMemberForm()
    {
        $resultSet = $this->getStaffTable()->fetchAll(array(
            'enable' => 1
        ));
        $returnArray = array();
        foreach ($resultSet as $staff) {
            $returnArray[$staff->id] = $staff->staff_name;
        }
        return $returnArray;
    }

    public function indexAction()
    {
        return array();
    }

    public function getMemberListDataAction()
    {
        try {
            $searchStr = $_GET['search']['value'];
            parse_str($searchStr, $searchArr);
          
            $where = function (Where $where) use($searchArr)
            {
                $where->equalTo('is_deleted', 0);
                if( !empty($searchArr['name']) ){ $where->like('member.name', '%' . $searchArr['name'] . '%'); }
                if( !empty($searchArr['code']) ){ $where->like('member.code', '%' . $searchArr['code'] . '%'); }
                if( !empty($searchArr['phone']) ){ $where->like('member.phone', '%' . $searchArr['phone'] . '%'); }
                if( $searchArr['status'] != '' ){ $where->equalTo('member.status', $searchArr['status']); }
                if( !empty($searchArr['dob']) ){ $where->addPredicate(new Expression("right(date_format(member.dob,'%Y-%m-%d'),5) = '{$searchArr['dob']}'")); }
            };
            
            $count = $this->getMemberTable()->getFetchAllCounts($where);
            $products = $this->getMemberTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));
            $arrProducts = $products->toArray();
            foreach ($arrProducts as $key => $product){
                $arrProducts[$key]['DT_RowId'] = $product['id'];
                $arrProducts[$key]['created_time'] = date('Y-m-d', strtotime($product['created_time']));
                $arrProducts[$key]['dob'] = date('Y-m-d', strtotime($product['dob']));
            }
            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $arrProducts);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
            $returnJson->setErrorMessage($e->getMessage());
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $form = MemberForm::getInstance($this->getServiceLocator());
        $form->setStaff($this->getStaffForMemberForm());
        $request = $this->getRequest();
        $member = new Member();
        $form->bind($member);
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $memberTable = $this->getMemberTable();
                $member = $memberTable->saveMember($form->getData());
                $this->flashMessenger()->addSuccessMessage($member->name . ' 已添加');
                return $this->redirect()->toUrl('/member/profile/' . $member->id);
            } else {
                $this->flashMessenger()->addErrorMessage($form->getMessages());
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
            $member = $this->getMemberTable()->getOneById($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toUrl('/member');
        }

        $form = MemberForm::getInstance($this->getServiceLocator());
        $form->setStaff($this->getStaffForMemberForm());
        $form->bind($member);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $data['goods'] = 0;
            $form->setInputFilter($member->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $memberTable = $this->getMemberTable();
                $member = $memberTable->saveMember($member);
                $this->flashMessenger()->addSuccessMessage('会员 ' . $member->name . ' 已编辑');
                return $this->redirect()->toUrl('/member');
            } else {
                $this->flashMessenger()->addErrorMessage($form->getMessages());
            }
        }
        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $table = $this->getMemberTable();
        $id = (int) $this->params('id', 0);
        $member = $table->deleteById($id);
        $this->flashMessenger()->addSuccessMessage($member->name . ' 已禁用');
        return $this->redirect()->toUrl('/member');
    }

    public function disableMemberAction()
    {
        try {
            $id = $this->params('id', 0);
            $type = $_GET['disable-type'];
            $this->getMemberTable()->disableMemberById($id, $type);
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, array());
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function enableMemberAction()
    {
        try {
            $id = $this->params('id', 0);
            $this->getMemberTable()->enableMemberById($id);
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, array());
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function profileAction()
    {
        $id = (int) $this->params('id', 0);
        $goods = $this->getGoodsForMemberForm();
        $form = MemberGoodsForm::getInstance($this->getServiceLocator());
        $form->setGoods($goods);
        try {
            $member = $this->getMemberTable()->getOneById($id);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage($ex->getMessage());
            return $this->redirect()->toUrl('/member');
        }

        $form->get('member_id')->setValue($member->id);
        return array(
            'member' => $member,
            'goods' => $goods,
            'form' => $form
        );
    }

    public function getMemberGoodsListDataAction()
    {
        try {
            $where = array(
                'member_id' => $_GET['member_id']
            );
            $count = $this->getMemberGoodsTable()->getFetchAllCounts($where);
            $memberGoods = $this->getMemberGoodsTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $memberGoods);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function getMemberLogListDataAction()
    {
        try {
            $where = array(
                'member_id' => $_GET['member_id'],
                'member_action_log.is_deleted' => 0
            );
            $count = $this->getMemberLogTable()->getFetchAllCounts($where);
            $memberGoods = $this->getMemberLogTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $memberGoods);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function deleteMemberGoodsAction()
    {
        try {
            $id = $this->params('id', 0);
            $this->getMemberGoodsTable()->deleteById($id);

            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, array());
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function deleteMemberLogAction()
    {
        try {
            $id = $this->params('id', 0);
            $log = $this->getMemberLogTable()->getOneById($id);
            $this->getMemberLogTable()->deleteById($id);
            if ($log->action == MemberLog::MEMBER_LOG_ACTION_TYPE_USE && $log->is_deleted == 0) {
                $memberGoods = $this->getMemberGoodsTable()->getOneById($log->member_goods_id);
                $memberGoods->count += $log->count;
                $memberGoods->enable = 1;
                $this->getMemberGoodsTable()->save($memberGoods);
            }

            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, array());
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }
}
