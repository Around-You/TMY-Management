<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class MemberGoodsTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    public $currentUserId = 0;

    protected $tableName = 'member_has_goods';

    protected $modelClassName = 'Application\\Model\\Goods\\MemberGoods';

    public function buildSqlSelect(Select $select, $where)
    {
        $select->join('goods', 'goods.id=goods_id', array(
            'goods_title' => 'title',
            'goods_type' => 'type',
            'goods_code' => 'code'
        ));
        $select->join('member', 'member.id=member_id', array(
            'memeber_name' => 'name',
            'memeber_code' => 'code'
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

    /**
     *
     * @param unknown $id
     * @throws \Exception
     * @return MemberGoods
     */
    public function getOneById($id)
    {
        $tableGateway = $this->getTableGateway();
        $id = (int) $id;
        $rowset = $tableGateway->select(function (Select $select) use($id){
            $select->where( array(
                $this->tableName . '.id' => $id
            ));
            $select->join('goods', 'goods.id=goods_id', array(
                'goods_title' => 'title',
                'goods_type' => 'type',
                'goods_code' => 'code'
            ));
        });
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row $id");
        }

        return $row;
    }

    public function save(MemberGoods $item)
    {
        $tableGateway = $this->getTableGateway();
        $data = $item->getArrayCopyForSave();
        $id = (int) $item->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $item->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getOneById($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $item;
    }

    public function deleteById($id)
    {
        $this->getTableGateway()->delete(array(
            'id' => (int) $id
        ));
    }
}

