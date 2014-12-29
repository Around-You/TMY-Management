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
use Application\Model\Json\JsonResult;
use Application\Model\Goods\Goods;
use Application\Model\Member\Member;
use Application\Model\Logs\SellLog;
use Application\Model\Goods\MemberGoods;
use Application\Model\Logs\MemberLog;

class SaleController extends AbstractActionController
{

    protected $memberTable;

    protected $goodsTable;

    protected $sellLogTable;

    protected $memberLogTable;

    protected $memberGoodsTable;

    protected $dailyReportTalbe;

    public function getMemberTable()
    {
        if (! $this->memberTable) {
            $this->memberTable = $this->getServiceLocator()->get('Application\Model\Member\MemberTable');
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

    public function quickAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $member = null;
            $memberCode = $this->params()->fromPost('member_code');
            $goodsCodeArr = $this->params()->fromPost('goods_code_arr', '');
            $goodsCodeArr = explode(',', $goodsCodeArr);
            if ($memberCode) {
                $member = $this->getMemberTable()->getMemberByCode($memberCode);
            }
            if (! empty($goodsCodeArr)) {
                foreach ($goodsCodeArr as $goodsCode) {
                    $goods = $this->getGoodsTable()->getGoodsByCode($goodsCode);
                    $this->addSellLog($goods, $member);
                    if ($goods->isVirtual()) {
                        $this->addToMemberGoods($goods, $member);
                    }
                    $this->getDailyReportTable()->addSaleCount();
                    $goods->doBuyGoods();
                    $this->getGoodsTable()->save($goods);
                }
                $this->flashMessenger()->addSuccessMessage('购买完成');
            } else {
                throw new \Exception('no goods code');
            }
        }
        return array();
    }

    public function useAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = $this->params()->fromPost('member_goods_code');

            if ($id > 0) {
                // update MemberGoods
                $memberGoods = $this->getMemberGoodsTable()->getOneById($id);
                $memberGoods->useGoods();
                $this->getMemberGoodsTable()->save($memberGoods);
                // add action log
                $action = new MemberLog();
                $action->goods_id = $memberGoods->goods_id;
                $action->member_id = $memberGoods->member_id;
                $action->user_id = App::getUser()->id;
                $action->action = '扣次/使用';
                $this->getMemberLogTable()->save($action);
                // add daily report
                $this->getDailyReportTable()->addMemberCount();

                $this->flashMessenger()->addSuccessMessage('扣次/使用完成');
                return array(
                    'ticketUrl' => '/sale/printUseConfirmTicket/' . $action->id
                );
            } else {
                throw new \Exception('ID不正确');
            }
        }
        return array(
            'ticketUrl' => ''
        );
    }

    public function printUseConfirmTicketAction()
    {
        $memberLogId = $this->params('id', 0);
        $memberLog = $this->getMemberLogTable()->getOneById($memberLogId);
        $member = $this->getMemberTable()->getOneById($memberLog->member_id);
        $usedGoods = $this->getGoodsTable()->getOneById($memberLog->goods_id);
        $memberGoods = $this->getMemberGoodsTable()->fetchAll(array(
                'member.id' => $memberLog->member_id
            ));;
        return array(
        	'member'=>$member,
            'usedGoods' => $usedGoods,
            'memberGoodsList'=>$memberGoods
        );
    }

    public function getMemberDataAction()
    {
        $table = $this->getMemberTable();
        $memberCode = $_GET['member_code'];
        try {
            $member = $table->getMemberByCode($memberCode);
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, $member);
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function getGoodsDataAction()
    {
        $table = $this->getGoodsTable();
        $code = $_GET['goods_code'];
        try {
            $goods = $table->getGoodsByCode($code);
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, $goods);
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function getMemberGoodsDataAction()
    {
        $table = $this->getMemberGoodsTable();
        $memberCode = $_GET['member_code'];
        try {
            $memberGoods = $table->fetchAll(array(
                'member.code' => $memberCode
            ));
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, $memberGoods);
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildFailedResult($e);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function doUseGoodsAction()
    {
        $returnJson = JsonResult::buildSuccessResult(array(
            'url' => '/sale/use'
        ));
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function addSellLog(Goods $goods, Member $member = null)
    {
        $log = new SellLog();
        if ($member) {
            $log->member_id = $member->id;
        }
        $log->goods_id = $goods->id;
        $log->user_id = App::getUser()->id;
        $log->price = $goods->price;
        $log->quantity = 1;
        $this->getSellLogTable()->saveSellLog($log);
    }

    public function addToMemberGoods(Goods $goods, Member $member)
    {
        $memberGoods = new MemberGoods();
        $memberGoods->setGoods($goods, time());
        $memberGoods->member_id = $member->id;
        $this->getMemberGoodsTable()->save($memberGoods);
    }
}
