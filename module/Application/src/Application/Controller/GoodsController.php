<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use SamFramework\Core\App;
use Application\Form\GoodsForm;
use Application\Model\Goods\Goods;

class GoodsController extends AbstractActionController
{

    protected $goodsTable;



    protected $categoryTable;

    public function getGoodsTable()
    {
        if (! $this->goodsTable) {
            $this->goodsTable = $this->getServiceLocator()->get('Application\Model\Goods\GoodsTable');
//             $this->goodsTable->currentUserId = App::getUser()->id;
        }

        return $this->goodsTable;
    }


    public function getCategoryTable()
    {
        if (! $this->categoryTable) {
            $this->categoryTable = $this->getServiceLocator()->get('Application\Model\Goods\CategoryTable');
            $this->categoryTable->currentUserId = App::getUser()->id;
        }

        return $this->categoryTable;
    }

    public function indexAction()
    {
        $user = App::getUser();
        return array();
    }

    public function getGoodsListDataAction()
    {
        $count = $this->getGoodsTable()->getFetchAllCounts();
        $products = $this->getGoodsTable()->fetchAll($_GET['start'], $_GET['length']);
        $listData = array(
            'draw' => $_GET['draw'] ++,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => array()
        );
        foreach ($products as $product) {
            $listData['data'][] = array(
                'DT_RowId' => $product->id,
                'title' => $product->title,
                'type' => $product->type,
                'category' => $product->category_name,
                'price' => $product->priceString,
                'desc' => $product->descString,
            );
        }
        $viewModel = new JsonModel($listData);
        return $viewModel;
    }

    public function addAction()
    {
        $form = GoodsForm::getInstance($this->getServiceLocator());
        $form->setCategories($this->getCategoryTable()->fetchAll());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $goods = new Goods();
            $form->setInputFilter($goods->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $goods->exchangeArray($form->getData());
                $goodsTable = $this->getGoodsTable();
                $goods = $goodsTable->saveGoods($goods);
                $this->flashMessenger()->addSuccessMessage($goods->title . ' 已添加');
                return $this->redirect()->toUrl('/goods');
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
            $product = $this->getGoodsTable()->getProduct($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toUrl('/product/product');
        }

        $form = ProductForm::getInstance($this->getServiceLocator());
        $form->bind($product);
        $form->setCategories($this->getCategoryTable()->fetchAll());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $productTable = $this->getGoodsTable();
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


}
