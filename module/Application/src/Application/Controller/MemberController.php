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

class MemberController extends AbstractActionController
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

    public function getMemberListDataAction()
    {
        $count = $this->getMemberTable()->getFetchAllCounts();
        $members = $this->getMemberTable()->fetchAll($_GET['start'], $_GET['length']);
        $listData = array(
            'draw' => $_GET['draw'] ++,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => array()
        );
        foreach ($members as $member) {
            $listData['data'][] = array(
                'DT_RowId' => $member->id,
                'name' => $member->name,
                'code' => $member->code,
                'phone' => $member->phone,
                'parent_name' => $member->parent_name,
                'point' => $member->point
            );
        }
        $viewModel = new JsonModel($listData);
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

    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        if (! $id) {
            return $this->redirect()->toUrl('/product/product/add');
        }
        try {
            $product = $this->getMemberTable()->getProduct($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toUrl('/product/product');
        }

        $form = ProductForm::getInstance($this->getServiceLocator());
        $form->bind($product);
        $form->setCategories($this->getGoodsTable()
            ->fetchAll());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $productTable = $this->getMemberTable();
                $product = $productTable->saveProduct($product);
                $this->flashMessenger()->addSuccessMessage($product->title . ' 已编辑');
                return $this->redirect()->toUrl('/product/product');
            }
        }
        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        print_r(realpath('.'));
        exit();
    }

    public function recommendAction()
    {
        $table = $this->getMemberTable();
        $id = (int) $this->params()->fromPost('id');
        $product = $table->getProduct($id);
        $product->recommend = (int) $this->params()->fromPost('recommend');

        $table->saveProduct($product);
        $this->flashmessenger()->addSuccessMessage($product->title . ($product->recommend == 1 ? ' 已推荐' : ' 已取消推荐'));
        return new FlashMessagerModel();
    }
}
