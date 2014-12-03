<?php
namespace Application\Controller\Product;

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

class ProductController extends AbstractActionController
{

    protected $projectTable;

    protected $projectImageTable;

    protected $categoryTable;

    public function getProductTable()
    {
        if (! $this->projectTable) {
            $this->projectTable = $this->getServiceLocator()->get('Application\Model\Product\ProductTable');
            $this->projectTable->currentUserId = App::getUser()->id;
        }

        return $this->projectTable;
    }

    public function getProductImageTable()
    {
        if (! $this->projectImageTable) {
            $this->projectImageTable = $this->getServiceLocator()->get('Application\Model\Product\ProductImageTable');
        }

        return $this->projectImageTable;
    }

    public function getCategoryTable()
    {
        if (! $this->categoryTable) {
            $this->categoryTable = $this->getServiceLocator()->get('Application\Model\Product\CategoryTable');
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

    public function recommendAction()
    {
        $table = $this->getProductTable();
        $id = (int) $this->params()->fromPost('id');
        $product = $table->getProduct($id);
        $product->recommend = (int) $this->params()->fromPost('recommend');

        $table->saveProduct($product);
        $this->flashmessenger()->addSuccessMessage($product->title . ($product->recommend == 1 ? ' 已推荐' : ' 已取消推荐'));
        return new FlashMessagerModel();
    }

    public function uploadImageAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $file = $this->getRequest()->getFiles('file');
        $product_id = (int) $this->params()->fromPost('product_id', 0);

        if ($file) {
            $desFileName = './public' . $config['system_params']['upload']['upload_file_path'] . '/product.jpg';
            $filter = new RenameUpload(array(
                'target' => $desFileName,
                'randomize' => true,
                'use_upload_extension' => true
            ));
            $result = $filter->filter($file);

            $uploadedImageInfo = pathinfo($result['tmp_name']);
            $imageUri = $config['system_params']['upload']['hostname'] . $config['system_params']['upload']['upload_file_path'] . '/' . $uploadedImageInfo['basename'];
            $thumbImageFileName = $uploadedImageInfo['filename'] . '_thumb.' . $uploadedImageInfo['extension'];
            $thumbImagePath = $uploadedImageInfo['dirname'] . '/' . $thumbImageFileName;
            $thumbImageUri = $config['system_params']['upload']['hostname'] . $config['system_params']['upload']['upload_file_path'] . '/' . $thumbImageFileName;

            $phpThumb = new GD($result['tmp_name']);
            $phpThumb->adaptiveResize(100, 100);
            $phpThumb->save($thumbImagePath);

            $productImage = new ProductImage();
            $productImage->file_path = $result['tmp_name'];
            $productImage->uri = $imageUri;
            $productImage->thumbnail_uri = $thumbImageUri;
            $productImage->name = $file["name"];
            $productImage->product_id = $product_id;
            $table = $this->getProductImageTable();
            $productImage = $table->saveProductImage($productImage);
        }

        return new JsonModel($productImage->getArrayCopy());
    }

    public function removeImageAction()
    {
        $id = (int) $this->params()->fromPost('imageId', 0);
        $table = $this->getProductImageTable();
        $result = $table->deleteProductImage($id);
        return new JsonModel(array(
            $result
        ));
    }

    public function setDefaultImageAction()
    {
        $productId = (int) $this->params()->fromPost('id', 0);
        $imageId = (int) $this->params()->fromPost('imageId', 0);

        $table = $this->getProductImageTable();
        $result = $table->setImageAsDefault($imageId, $productId);
        return new JsonModel(array(
            $result
        ));
    }

    public function getBarCode()
    {
        $productId = (int) $this->params()->fromPost('id', 0);
        $url = '/p/' . $productId;
    }
}
