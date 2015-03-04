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
        $select->join('staff', 'staff.id=referral', array(
            'referral_staff_name' => 'staff_name'
        ), Select::JOIN_LEFT);
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
     * @return Member
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

    /**
     *
     * @param string $code
     * @throws \Exception
     * @return Zend\Db\ResultSet\ResultSet
     */
    public function getMembersByCode($code)
    {
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($code)
        {
            $select->where->like('code', $code . '%');
            $select->where->equalTo('enable', 1);
        });

        return $resultSet;
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

    public function changeStatusById($id, $status){
        $tableGateway = $this->getTableGateway();
        $model = $this->getOneById($id);
        $model->status = $status;
        $this->saveMember($model);
        return $model;
    }

    public function deleteById($id)
    {
        $tableGateway = $this->getTableGateway();
        $model = $this->getOneById($id);
        $model->is_deleted = 1;
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

    public function addPoint(Member $member, $point)
    {
        $member->point += round($point);
        $this->saveMember($member);
    }
}

