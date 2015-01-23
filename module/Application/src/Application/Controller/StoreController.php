<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\View\Model\JsonModel;
use Application\Model\Json\DataTableResult;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Expression;

class StoreController extends AbstractActionController
{

    protected $sellLogTable;

    public function getSellLogTable()
    {
        if (! $this->sellLogTable) {
            $this->sellLogTable = $this->getServiceLocator()->get('Application\Model\Logs\SellLogTable');
        }
        return $this->sellLogTable;
    }

    public function sellLogAction()
    {
        return array();
    }

    public function getSellLogListDataAction()
    {
        try {
            $search = $_GET['search']['value'];
            $where = function (Where $where) use($search)
            {
                if (! empty($search)) {
                    $where->addPredicate(new Expression(" goods.code like '{$search}%' or member.code like '{$search}%' "));
                }
            };
            $count = $this->getSellLogTable()->getFetchAllCounts($where);
            $logs = $this->getSellLogTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $logs);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function memberLogAction()
    {
        return array();
    }

    public function getMemberLogListDataAction()
    {
        try {
            $search = $_GET['search']['value'];
            $where = function (Where $where) use($search)
            {
                $where->addPredicate(new Expression(" goods.code like '{$search}%' or member.code like '{$search}%' "));
            };
            $count = $this->getMemberLogTable()->getFetchAllCounts($where);
            $logs = $this->getMemberLogTable()->fetchAll($where, $_GET['start'], $_GET['length'], DataTableResult::getOrderString($_GET));

            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $logs);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }
}
