<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class UserTable extends AbstractModelMapper
{

    protected $tableName = 'user';

    protected $modelClassName = 'Application\Model\Account\User';

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

    public function getUser($id)
    {
        $id = (int) $id;
        $rowset = $this->getTableGateway()->select(array(
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
     * @param unknown $token
     * @throws \Exception
     * @return User
     */
    public function getUserByWeiBoToken($token)
    {
        $tableGateway = $this->getTableGateway();
        $rowset = $tableGateway->select(array(
            'weibo_token' => $token
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row by token: $token");
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $tableGateway = $this->getTableGateway();
        $data = $user->getArrayCopyForSave();
        $id = (int) $user->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $user->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getUser($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $user;
    }
}

