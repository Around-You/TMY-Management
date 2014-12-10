<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;

class CategoryTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    protected $tableName = 'category';

    protected $modelClassName = 'Application\\Model\\Goods\\Category';

    public function buildSqlSelect(Select $select){
//         $select->where('store_id='.$this->currentStoreId);
    }

    public function getFetchAllCounts()
    {
        $select = $this->getTableGateway()->getSql()->select();
        $this->buildSqlSelect($select);
        $select->columns(array('id'));
        $statement = $this->getTableGateway()->getSql()->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        return $results->count();
    }

    public function fetchAll($offset = 0, $limit = 1000)
    {
        $offset = (int)$offset;
        $limit = (int)$limit;

        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($offset, $limit, $table)
        {

            $table->buildSqlSelect($select);
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

