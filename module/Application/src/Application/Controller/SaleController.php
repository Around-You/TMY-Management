<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use SamFramework\Core\App;
use Zend\View\Model\JsonModel;
use Application\Model\Json\JsonResult;
use Application\Model\Goods\Goods;
use Application\Model\Member\Member;
use Application\Model\Goods\MemberGoods;
use Application\Model\Logs\MemberLog;

class SaleController extends AbstractActionController
{

    public function buyMemberCardAction()
    {
        $request = $this->getRequest();

        try {
            $memberId = $_GET['member_id'];
            $goodsId = $_GET['goods_id'];
            $member = $this->getMemberTable()->getOneById($memberId);
            $goods = $this->getGoodsTable()->getOneById($goodsId);
            $memberGoods = new MemberGoods();
            $memberGoods->exchangeArray($_GET);
            $this->getMemberGoodsTable()->save($memberGoods);
            $this->getDailyReportTable()->addSaleCount();
            $this->getSellLogTable()->addSellLog($goods, $member);
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_SUCCESSFUL, array());
        } catch (\Exception $e) {
            $returnJson = JsonResult::buildResult(JsonResult::JSON_RESULT_FAILED);
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
        return $viewModel;
    }

    public function quickAction()
    {
        $request = $this->getRequest();
        $resultSet = $this->getGoodsTable()->getAllEnableGoods();
        $goods = $this->getGoodsTable()->formatGoodsResultSetToSelect($resultSet);
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
                    $this->getSellLogTable()->addSellLog($goods, $member);
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
        return array(
            'goods' => $goods
        );
    }

    public function useAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $id = $this->params()->fromPost('member_goods_code');
            $count = $this->params()->fromPost('use_count');

            if ($id > 0) {
                // update MemberGoods
                $memberGoods = $this->getMemberGoodsTable()->getOneById($id);
                $memberGoods->useGoods($count);
                $this->getMemberGoodsTable()->save($memberGoods);
                // add action log
                $action = new MemberLog();
                $action->member_goods_id = $memberGoods->id;
                $action->goods_id = $memberGoods->goods_id;
                $action->member_id = $memberGoods->member_id;
                $action->user_id = App::getUser()->id;
                $action->action = $memberGoods->goods_type == '时间卡' ? '打印' : '扣次/使用';
                $action->count = $count;
                $this->getMemberLogTable()->save($action);
                // add daily report
                $this->getDailyReportTable()->addMemberCount($count);

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
            'member.id' => $memberLog->member_id,
            'goods_id' => $memberLog->goods_id
        ));
        ;
        return array(
            'member' => $member,
            'usedGoods' => $usedGoods,
            'memberGoodsList' => $memberGoods->toArray(),
            'memberLog' => $memberLog
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
        $id = $_GET['goods_id'];
        try {
            $goods = $table->getOneById($id);
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
                'member.code' => $memberCode,
                'member_has_goods.enable' => 1
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

    public function getMemberByCodeAction()
    {
        $code = $_GET['term'];
        $members = $this->getMemberTable()->getMembersByCode($code);
        $memberArr = array();
        foreach ($members as $member) {
            $memberArr[] = array(
                'id' => $member->code,
                'value' => $member->code . ' - ' . $member->name
            );
        }
        $viewModel = new JsonModel($memberArr);
        return $viewModel;
    }

    public function addToMemberGoods(Goods $goods, Member $member)
    {
        $memberGoods = new MemberGoods();
        $memberGoods->setGoods($goods, time());
        $memberGoods->member_id = $member->id;
        $this->getMemberGoodsTable()->save($memberGoods);
    }
}
