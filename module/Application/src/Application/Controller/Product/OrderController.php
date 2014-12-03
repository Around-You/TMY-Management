<?php
namespace Admin\Controller\Product;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class OrderController extends AbstractActionController
{

    protected $productTable;

    public function getProductTable()
    {
        if (! $this->productTable) {
            $this->productTable = $this->getServiceLocator()->get('Application\Model\Product\ProductOrderTable');
            $this->productTable->currentUserId = $this->identity()->id;
        }
        return $this->productTable;
    }


    public function indexAction()
    {
        return array();
    }

    public function getOrderDataAction()
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
                'recommend' => $product->recommend ? '已推荐' : '',
                'count' => $product->countOfOrders,
            );
        }
        $viewModel = new JsonModel($listData);
        return $viewModel;
    }
}
