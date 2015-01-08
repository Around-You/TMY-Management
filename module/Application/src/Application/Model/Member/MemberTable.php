<?php
namespace Application\Model\Member;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class MemberTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    public $currentUserId = 0;

    protected $tableName = 'member';

    protected $modelClassName = 'Application\\Model\\Member\\Member';

    public function buildSqlSelect(Select $select, $where)
    {
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
     * @return Member
     */
    public function getMemberByCode($code)
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

    public function deleteById($id)
    {
        $tableGateway = $this->getTableGateway();
        $model = $this->getOneById($id);
        $model->enable = 0;
        $this->saveMember($model);
        return $model;
    }

    /**
     *
     * @param Member $member
     * @return Member
     */
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
            if ($this->getOneById($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }

        return $member;
    }
}

