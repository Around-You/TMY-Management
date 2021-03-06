<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class StaffTable extends AbstractModelMapper
{

    protected $tableName = 'staff';

    protected $modelClassName = 'Application\Model\Account\Staff';

    public function buildSqlSelect(Select $select, $where = array())
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

    /**
     *
     * @param unknown $id
     * @throws \Exception
     * @return Staff
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
     * @param unknown $loginName
     * @throws \Exception
     * @return Staff
     */
    public function getOneByLoginName($loginName)
    {
        $tableGateway = $this->getTableGateway();
        $rowset = $tableGateway->select(array(
            'login_name' => $loginName
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row $loginName");
        }

        return $row;
    }


    /**
     *
     * @param unknown $id
     * @return Staff
     */
    public function deleteById($id)
    {
        $tableGateway = $this->getTableGateway();
        $model = $this->getOneById($id);
        $model->enable = 0;
        $this->save($model);
        return $model;
    }

    public function save(Staff $items)
    {
        $tableGateway = $this->getTableGateway();
        $items->update_time = date('YmdHis');
        $data = $items->getArrayCopyForSave();
        $id = (int) $items->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $items->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getOneById($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }

        return $items;
    }
}

