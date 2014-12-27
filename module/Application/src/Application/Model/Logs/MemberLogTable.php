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

    public function buildSqlSelect(Select $select, $where = array())
    {
//         $select->join('category', 'category.id=category_id', array(
//             'category_name' => 'title'
//         ));
        $select->where($where);
    }

    public function getFetchAllCounts()
    {
        $select = $this->getTableGateway()
            ->getSql()
            ->select();
        $this->buildSqlSelect($select);
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

    public function save(MemberLog $item)
    {
        $tableGateway = $this->getTableGateway();
        $data = $item->getArrayCopyForSave();
        $id = (int) $item->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $item->id = $this->getTableGateway()->getLastInsertValue();
        } else {
//             if ($this->getProduct($id)) {
//                 $tableGateway->update($data, array(
//                     'id' => $id
//                 ));
//             }
        }
        return $item;
    }

}
