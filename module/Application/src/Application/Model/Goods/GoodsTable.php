<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class GoodsTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    protected $tableName = 'goods';

    protected $modelClassName = 'Application\\Model\\Goods\\Goods';

    public function buildSqlSelect(Select $select, $where)
    {
        $select->join('category', 'category.id=category_id', array(
            'category_name' => 'title'
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

    /**
     *
     * @param string $code
     * @throws \Exception
     * @return Zend\Db\ResultSet\ResultSet
     */
    public function getAllGoodsLikeCode($code)
    {
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($code)
        {
            $select->where->like('code', $code . '%');
        });

        return $resultSet;
    }

    /**
     *
     * @param string $code
     * @throws \Exception
     * @return Goods
     */
    public function getGoodsByCode($code)
    {
        $tableGateway = $this->getTableGateway();
        $rowset = $tableGateway->select(array(
            'code' => $code
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row $code");
        }

        return $row;
    }

    /**
     *
     * @param unknown $id
     * @return Goods
     */
    public function deleteById($id)
    {
        $tableGateway = $this->getTableGateway();
        $model = $this->getOneById($id);
        $model->enable = 0;
        $tableGateway->update($model->getArrayCopyForSave(), array(
            'id' => $id
        ));
        return $model;
    }

    public function save(Goods $goods)
    {
        $tableGateway = $this->getTableGateway();
        $goods->update_time = date('YmdHis');
        // $goods->user_id = $this->currentStoreId;
        $data = $goods->getArrayCopyForSave();
        $id = (int) $goods->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $goods->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getOneById($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }

        return $goods;
    }

    public function buyGoods($goodsArr, $memberCode = '')
    {}
}

