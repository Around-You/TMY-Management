<?php
namespace Application\Model\Logs;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Application\Model\Goods\Goods;
use Application\Model\Member\Member;
use SamFramework\Core\App;

class SellLogTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    public $currentUserId = 0;

    protected $memberTable;

    protected $tableName = 'sell_log';

    protected $modelClassName = 'Application\\Model\\Logs\\SellLog';

    public function getMemberTable()
    {
        if (! $this->memberTable) {
            $this->memberTable = $this->getServiceLocator()->get('Application\Model\Member\MemberTable');
        }
        return $this->memberTable;
    }

    public function buildSqlSelect(Select $select, $where)
    {
        $select->join('member', 'member.id=member_id', array(
            'member_name' => 'name',
            'member_code' => 'code'
        ), $select::JOIN_LEFT);
        $select->join('goods', 'goods.id=goods_id', array(
            'goods_title' => 'title'
        ));
        $select->join('staff', 'staff.id=user_id', array(
            'staff_name' => 'staff_name'
        ));
        $select->where($where);
    }

    public function getFetchAllCounts($where = array())
    {
        $select = $this->getTableGateway()
            ->getSql()
            ->select();
        $this->buildSqlSelect($select, $where);
        $select->columns(array(
            new Expression('count(' . $this->tableName . '.id) as rownum')
        ));
        $statement = $this->getTableGateway()
            ->getSql()
            ->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return $row['rownum'];
    }

    public function fetchAll($where = array(), $offset = 0, $limit = 99999)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;

        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($offset, $limit, $table, $where)
        {
            $table->buildSqlSelect($select, $where);
            $select->offset($offset)
                ->limit($limit);
        });
        return $resultSet;
    }

    public function saveSellLog(SellLog $item)
    {
        $tableGateway = $this->getTableGateway();
        $data = $item->getArrayCopyForSave();
        $id = (int) $item->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $item->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getProduct($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $item;
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
        $this->saveSellLog($log);
        $this->afterAddedSellLog($goods, $member);
    }

    public function afterAddedSellLog(Goods $goods, Member $member)
    {
        $this->getMemberTable()->addPoint($member, $goods->price);
    }


}

