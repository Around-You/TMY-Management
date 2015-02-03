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
            $search = $_GET['search']['value'];
            $where = function (Where $where) use($search)
            {
                $where->addPredicate(new Expression("member.enable = 1 and (member.code like '{$search}%' or member.phone like '{$search}%' or member.name like '{$search}%')"));
            };
            $count = $this->getMemberTable()->getFetchAllCounts($where);
            $products = $this->getMemberTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $products);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $form = MemberForm::getInstance($this->getServiceLocator());
        // $form->setMemberGoods($this->getGoodsForMemberForm());
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

                // if ($member->goods > 0) {
                // $goods = $this->getGoodsTable()->getOneById($member->goods);
                // $this->getSellLogTable()->addSellLog($goods, $member);
                // $memberGoods = new MemberGoods();
                // $memberGoods->setGoods($goods);
                // $memberGoods->member_id = $member->id;
                // $this->getMemberGoodsTable()->save($memberGoods);
                // }
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
                'member_id' => $_GET['member_id']
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
}
