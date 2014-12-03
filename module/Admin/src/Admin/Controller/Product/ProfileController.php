<?php
namespace Admin\Controller\Product;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ProfileController extends AbstractActionController
{

    protected $productTable;

    protected $orderTable;

    protected $productId = 0;

    public function getProductTable()
    {
        if (! $this->productTable) {
            $this->productTable = $this->getServiceLocator()->get('Application\Model\Product\ProductTable');
            $this->productTable->currentUserId = $this->identity()->id;
        }
        return $this->productTable;
    }

    public function getOrderTable()
    {
        if (! $this->orderTable) {
            $this->orderTable = $this->getServiceLocator()->get('Application\Model\Product\OrderTable');
            $this->orderTable->productId = $this->productId;
        }

        return $this->orderTable;
    }

    public function indexAction()
    {
        $id = (int) $this->params('id');
        if ($id > 0) {} else {
            throw new \Exception('参数不正确', 500);
        }

        $product = $this->getProductTable()->getProduct($id);
        $buyer = $this->getOrderTable()->fetchAll();

        $viewModel = new ViewModel(array(
            'product' => $product
        ));
        return $viewModel;
    }

    public function getBuyerDataAction()
    {
        $this->productId = (int) $this->params('id');
        $count = $this->getOrderTable()->getFetchAllCounts();
        $orders = $this->getOrderTable()->fetchAll($_GET['start'], $_GET['length']);
        $listData = array(
            'draw' => $_GET['draw'] ++,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => array()
        );
        foreach ($orders as $order) {
            $listData['data'][] = array(
                'DT_RowId' => $order->id,
                'buyer_weixin' => $order->buyer_weixin,
                'quantity' => $order->quantity,
                'total' => $order->total
            );
        }
        $viewModel = new JsonModel($listData);
        return $viewModel;
    }
}
