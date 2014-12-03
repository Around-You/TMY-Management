<?php
namespace CodeGenerator\Template;

use SamFramework\src\Model\TableAbstract;

class ModelMapperTemplate extends TableAbstract
{

    const TABLE_NAME = '';

    const MODEL_CLASS_NAME = 'Application\Model\Product\Product';

    public function fetchAll()
    {
        $resultSet = $this->getTableGateway()->select();
        return $resultSet;
    }

    public function getModel()
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

    public function deleteModel()
    {
        $this->tableGateway->delete(array(
            'id' => (int) $id
        ));
    }
}

