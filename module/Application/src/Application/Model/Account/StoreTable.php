<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class StoreTable extends AbstractModelMapper
{

    protected $tableName = 'store';

    protected $modelClassName = 'Application\\Model\\Store';



    public function saveStore(Store $store)
    {
        $tableGateway = $this->getTableGateway();
        $data = $store->getArrayCopyForSave();
        $id = (int) $buyer->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $buyer->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getUser($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $store;
    }
}

