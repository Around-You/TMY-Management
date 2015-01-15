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
use Zend\View\Model\JsonModel;
use Application\Form\MemberForm;
use Application\Model\Goods\Goods;
use Application\Model\Member\Member;
use Application\Model\Goods\MemberGoods;
use Application\Model\Json\DataTableResult;

class MemberController extends AbstractActionController
{

    protected $memberTable;

    protected $goodsTable;

    protected $memberGoodsTable;

    protected $sellLogTable;

    public function getMemberTable()
    {
        if (! $this->memberTable) {
            $this->memberTable = $this->getServiceLocator()->get('Application\Model\Member\MemberTable');
            $this->memberTable->currentUserId = App::getUser()->id;
            $this->memberTable->currentStoreId = App::getUser()->store_id;
        }
        return $this->memberTable;
    }

    public function getGoodsTable()
    {
        if (! $this->goodsTable) {
            $this->goodsTable = $this->getServiceLocator()->get('Application\Model\Goods\GoodsTable');
        }
        return $this->goodsTable;
    }

    public function getMemberGoodsTable()
    {
        if (! $this->memberGoodsTable) {
            $this->memberGoodsTable = $this->getServiceLocator()->get('Application\Model\Goods\MemberGoodsTable');
        }
        return $this->memberGoodsTable;
    }

    public function getSellLogTable()
    {
        if (! $this->sellLogTable) {
            $this->sellLogTable = $this->getServiceLocator()->get('Application\Model\Logs\SellLogTable');
        }
        return $this->sellLogTable;
    }

    public function getGoodsUseForMember()
    {
        $table = $this->getGoodsTable();
        $resultSet = $table->fetchAll(array(
            'type' => array(
                Goods::GOODS_TYPE_COUNT,
                Goods::GOODS_TYPE_TIME
            )
        ));
        $returnArray = array();
        foreach ($resultSet as $good) {
            $returnArray[$good->id] = $good->title . ' - ' . $good->priceString;
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
            $count = $this->getMemberTable()->getFetchAllCounts(array(
                'member.enable' => 1
            ));
            $products = $this->getMemberTable()->fetchAll(array(
                'member.enable' => 1
            ), $_GET['start'], $_GET['length']);

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $products);
        } catch (\Exception $e) {
            var_dump($e);
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $form = MemberForm::getInstance($this->getServiceLocator());
        $form->setMemberGoods($this->getGoodsUseForMember());
        $request = $this->getRequest();
        $member = new Member();
        $form->bind($member);
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $memberTable = $this->getMemberTable();
                $member = $memberTable->saveMember($form->getData());

                if ($member->goods > 0) {
                    $goods = $this->getGoodsTable()->getOneById($member->goods);
                    $this->getSellLogTable()->addSellLog($goods, $member);
                    $memberGoods = new MemberGoods();
                    $memberGoods->setGoods($goods);
                    $memberGoods->member_id = $member->id;
                    $this->getMemberGoodsTable()->save($memberGoods);
                }
                $this->flashMessenger()->addSuccessMessage($member->name . ' 已添加');
                return $this->redirect()->toUrl('/member');
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
}
