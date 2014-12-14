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

class SaleController extends AbstractActionController
{

    protected $memberTable;

    protected $goodsTable;

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
    public function quickAction()
    {
        return array();
    }
    public function saleAction()
    {
        return array();
    }

    public function getMemberDataAction()
    {
        $table = $this->getMemberTable();
        $memberCode = $_GET['member_code'];
        $member = $table->getMemberByCode($memberCode);
        $viewModel = new JsonModel($member);
        return $viewModel;
    }

    public function addAction()
    {
        $form = MemberForm::getInstance($this->getServiceLocator());
        $form->setMemberGoods($this->getGoodsUseForMember());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $member = new Member();
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $member->exchangeArray($form->getData());
                $memberTable = $this->getMemberTable();
                $member = $memberTable->saveMember($member);
                $this->flashMessenger()->addSuccessMessage($member->name . ' 已添加');
                return $this->redirect()->toUrl('/member');
            }else{
                $this->flashMessenger()->addErrorMessage($form->getMessages());
            }
        }

        return array(
            'form' => $form
        );
    }

    public function getGoodsDataAction()
    {

    }

}
