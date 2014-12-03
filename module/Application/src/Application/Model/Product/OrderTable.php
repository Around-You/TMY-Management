<?php
namespace Application\Model\Product;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class OrderTable extends AbstractModelMapper
{

    public $productId = 0;

    protected $tableName = 'order';

    protected $modelClassName = 'Application\\Model\\Product\\Order';

    public function buildSqlSelect(Select $select)
    {
        $select->join('buyer', 'buyer.id=buyer_id', array(
            'buyer_weixin' => 'weixin'
        ));
        $select->where(array(
            'product_id' => $this->productId
        ));
    }

    public function getFetchAllCounts()
    {
        $select = $this->getTableGateway()
            ->getSql()
            ->select();
        $this->buildSqlSelect($select);
        $select->columns(array(
            'id'
        ));
        $statement = $this->getTableGateway()
            ->getSql()
            ->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        return $results->count();
    }

    public function fetchAll($offset = 0, $limit = 1000)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;

        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($offset, $limit, $table)
        {
            $table->buildSqlSelect($select);
            $select->offset($offset)
                ->limit($limit);
        });
        return $resultSet;
    }

    public function getOrder($id)
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

    public function deleteProductBuyer($id)
    {
        $this->tableGateway->delete(array(
            'id' => (int) $id
        ));
    }

    public function saveOrder(Order $order)
    {
        $tableGateway = $this->getTableGateway();
        $data = $order->getArrayCopyForSave();
        $id = (int) $order->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $order->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getOrder($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $order;
    }
}

