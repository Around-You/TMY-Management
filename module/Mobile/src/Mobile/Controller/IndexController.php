<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Mobile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $storeId;

    protected $projectTable;

    protected $categoryTable;

    /**
     * @param field_type $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        if ($this->storeId ==0) {
            throw new \Exception('您访问的店铺暂不存在');
        }
    }

	public function indexAction()
    {
        $this->setStoreId($this->params('storeId',0));
        $categoryTable = $this->getCategoryTable();
        $categories = $categoryTable->fetchAll();
        $products = $this->getProductTable()->getRecommendedProducts();
        return array(
            'products' => $products,
            'categories' => $categories
        );
    }

    public function getProductTable()
    {
        if (! $this->projectTable) {
            $this->projectTable = $this->getServiceLocator()->get('Application\Model\Product\ProductTable');
            $this->projectTable->currentStoreId = $this->storeId;
        }

        return $this->projectTable;
    }


    protected function getCategoryTable()
    {
        if (! $this->categoryTable) {
            $this->categoryTable = $this->getServiceLocator()->get('Application\Model\Product\CategoryTable');
            $this->categoryTable->currentStoreId = $this->storeId;
        }

        return $this->categoryTable;
    }
}
