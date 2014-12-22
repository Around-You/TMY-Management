<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use SamFramework\Core\App;
use Application\Form\GoodsForm;
use Application\Model\Goods\Goods;
use Application\Model\Json\DataTableResult;

class GoodsController extends AbstractActionController
{

    protected $goodsTable;

    protected $categoryTable;

    public function getGoodsTable()
    {
        if (! $this->goodsTable) {
            $this->goodsTable = $this->getServiceLocator()->get('Application\Model\Goods\GoodsTable');
            // $this->goodsTable->currentUserId = App::getUser()->id;
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
        try {
            $count = $this->getGoodsTable()->getFetchAllCounts();
            $products = $this->getGoodsTable()->fetchAll(array(), $_GET['start'], $_GET['length']);

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $products);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addAction()
    {
        $form = GoodsForm::getInstance($this->getServiceLocator());
        $form->setCategories($this->getCategoryTable()
            ->fetchAll());

        $goods = new Goods();
        $form->bind($goods);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($goods->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
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
        $form->setCategories($this->getCategoryTable()
            ->fetchAll());
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
