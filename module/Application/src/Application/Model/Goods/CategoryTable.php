<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class CategoryTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    protected $tableName = 'category';

    protected $modelClassName = 'Application\\Model\\Goods\\Category';

    public function buildSqlSelect(Select $select, $where){
        $select->where($where);
    }

    public function getFetchAllCounts($where = array())
    {
        $select = $this->getTableGateway()
            ->getSql()
            ->select();
        $this->buildSqlSelect($select,$where);
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

    public function getCategory($id)
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

    public function deleteCategory($id)
    {
        $this->tableGateway->delete(array(
            'id' => (int) $id
        ));
    }

    public function saveCategory(Category $category)
    {
        $tableGateway = $this->getTableGateway();
        $category->store_id = $this->currentStoreId;
        $data = $category->getArrayCopyForSave();
        $id = (int) $category->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $category->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getCategory($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $category;
    }
}

