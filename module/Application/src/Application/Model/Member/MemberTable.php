<?php
namespace Application\Model\Member;

use SamFramework\Model\AbstractModelMapper;
use Application\Model\Product\Product;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class MemberTable extends AbstractModelMapper
{
    public $currentStoreId = 0;
    public $currentUserId = 0;


    protected $tableName = 'member';


    protected $modelClassName = 'Application\\Model\\Member\\Member';



    public function buildSqlSelect(Select $select){


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

    public function fetchAll($offset = 0, $limit = 10)
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

    public function getMember($id)
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

    public function deleteProduct($id)
    {
        $this->tableGateway->delete(array(
            'id' => (int) $id
        ));
    }

    public function saveMember(Member $member)
    {
        $tableGateway = $this->getTableGateway();
        $member->update_time = date('YmdHis');
        $id = (int) $member->id;
        if ($id == 0) {
            $member->created_by_user = $this->currentUserId;
            $member->created_at_store = $this->currentStoreId;
            $data = $member->getArrayCopyForSave();
            $tableGateway->insert($data);
            $member->id = $this->getTableGateway()->getLastInsertValue();

        } else {
        $data = $member->getArrayCopyForSave();
            if ($this->getMember($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }

        return $member;
    }

    public function getProductsByCategory(Category $category)
    {
        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($category, $table)
        {
            $select->columns(array(
                'id',
                'title',
                'price',
                'unit',
                'recommend'
            ));
            $table->buildSqlSelect($select);
            $select->where("category_id={$category->id}");
            $select->where("enable=1");
        });
        return $resultSet;
    }


    public function getRecommendedProducts()
    {
        $resultSet = $this->getTableGateway()->select(array(
            'recommend' => 1,
            'enable' => 1,
            'store_id' => $this->currentStoreId
        ));
        return $resultSet;
    }
}

