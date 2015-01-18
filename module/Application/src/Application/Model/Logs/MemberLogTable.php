<?php
namespace Application\Model\Logs;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class MemberLogTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    public $currentUserId = 0;

    protected $tableName = 'member_action_log';

    protected $modelClassName = 'Application\\Model\\Logs\\MemberLog';

    public function buildSqlSelect(Select $select, $where)
    {
        $select->join('member', 'member.id=member_id', array(
            'member_name' => 'name',
            'member_code' => 'code'
        ), $select::JOIN_LEFT);
        $select->join('goods', 'goods.id=goods_id', array(
            'goods_title' => 'title',
            'goods_code' => 'code'
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

    public function fetchAll($where = array(), $offset = 0, $limit = 99999, $order = array())
    {
        $offset = (int) $offset;
        $limit = (int) $limit;

        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($offset, $limit, $table, $where, $order)
        {
            $table->buildSqlSelect($select, $where);
            $select->offset($offset)
                ->limit($limit)
                ->order($order);
        });
        return $resultSet;
    }

    public function save(MemberLog $item)
    {
        $tableGateway = $this->getTableGateway();
        $data = $item->getArrayCopyForSave();
        $id = (int) $item->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $item->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            // if ($this->getProduct($id)) {
            // $tableGateway->update($data, array(
            // 'id' => $id
            // ));
            // }
        }
        return $item;
    }

    /**
     *
     * @param unknown $id
     * @throws \Exception
     * @return MemberLog
     */
    public function getOneById($id)
    {
        $tableGateway = $this->getTableGateway();
        $id = (int) $id;
        $rowset = $tableGateway->select(array(
            'id' => $id
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row $id");
        }

        return $row;
    }
}

