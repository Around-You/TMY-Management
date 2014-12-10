<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Product\Product;
use Admin\Form\Product\ProductForm;
use Application\Model\Product\ProductImage;
use Zend\View\Model\JsonModel;
use Zend\Filter\File\RenameUpload;
use PHPThumb\GD;
use Components\Layout\View\Model\FlashMessagerModel;
use SamFramework\Core\App;

class GoodsController extends AbstractActionController
{

    protected $goodsTable;



    protected $categoryTable;

    public function getGoodsTable()
    {
        if (! $this->goodsTable) {
            $this->goodsTable = $this->getServiceLocator()->get('Application\Model\Product\ProductTable');
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

    public function getProcustsListDataAction()
    {
        $count = $this->getProductTable()->getFetchAllCounts();
        $products = $this->getProductTable()->fetchAll($_GET['start'], $_GET['length']);
        $listData = array(
            'draw' => $_GET['draw'] ++,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => array()
        );
        foreach ($products as $product) {
            $listData['data'][] = array(
                'DT_RowId' => $product->id,
                'img' => $product->product_thumbnail,
                'title' => $product->title,
                'category' => $product->category_name,
                'price' => $product->priceString,
                'unit' => $product->unit,
                'recommend' => $product->recommend ? '已推荐' : ''
            );
        }
        $viewModel = new JsonModel($listData);
        return $viewModel;
    }

    public function addAction()
    {
        $form = ProductForm::getInstance($this->getServiceLocator());
        $form->setCategories($this->getCategoryTable()->fetchAll());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $product = new Product();
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $product->exchangeArray($form->getData());
                $productTable = $this->getProductTable();
                $product = $productTable->saveProduct($product);
                $this->flashMessenger()->addSuccessMessage($product->title . ' 已添加');
                return $this->redirect()->toUrl('/product/product');
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
            $product = $this->getProductTable()->getProduct($id);
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
                $productTable = $this->getProductTable();
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
