<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZFAAC;
use SamFramework\Core\App;

class AbstractActionController extends ZFAAC
{

    protected $memberTable;

    protected $goodsTable;

    protected $sellLogTable;

    protected $memberLogTable;

    protected $memberGoodsTable;

    protected $dailyReportTalbe;

    protected $staffTable;

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

    public function getSellLogTable()
    {
        if (! $this->sellLogTable) {
            $this->sellLogTable = $this->getServiceLocator()->get('Application\Model\Logs\SellLogTable');
        }
        return $this->sellLogTable;
    }

    public function getMemberLogTable()
    {
        if (! $this->memberLogTable) {
            $this->memberLogTable = $this->getServiceLocator()->get('Application\Model\Logs\MemberLogTable');
        }
        return $this->memberLogTable;
    }

    public function getMemberGoodsTable()
    {
        if (! $this->memberGoodsTable) {
            $this->memberGoodsTable = $this->getServiceLocator()->get('Application\Model\Goods\MemberGoodsTable');
        }
        return $this->memberGoodsTable;
    }

    public function getDailyReportTable()
    {
        if (! $this->dailyReportTalbe) {
            $this->dailyReportTalbe = $this->getServiceLocator()->get('Application\Model\Report\DailyReportTable');
        }
        return $this->dailyReportTalbe;
    }

    public function getStaffTable()
    {
        if (! $this->staffTable) {
            $this->staffTable = $this->getServiceLocator()->get('Application\Model\Account\StaffTable');
        }
        return $this->staffTable;
    }
}

