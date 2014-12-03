<?php
namespace SamFramework\Model;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use SamFramework\Core\AbstractAutoBuilder;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;

abstract class AbstractModelMapper extends AbstractAutoBuilder
{

    protected $tableName;

    protected $modelClassName;

    protected $adapter;

    protected $tableGateway = null;

    public function getTableName()
    {
        if ($this->tableName === null) {
            throw new \RuntimeException('You must setup Table Name', 500);
        }
        return $this->tableName;
    }

    public function getModelClassName()
    {
        if ($this->modelClassName === null) {
            throw new \RuntimeException('You must setup Model Class Name', 500);
        }
        return $this->modelClassName;
    }

    /**
     *
     * @return TableGateway $tableGateway
     */
    public function getTableGateway()
    {
        if (! $this->tableGateway) {
            $resultSetPrototype = new ResultSet();
            $modelClass = $this->getModelClassName();
            $resultSetPrototype->setArrayObjectPrototype(new $modelClass());
            $this->tableGateway = new TableGateway($this->getTableName(), $this->getAdapter(), null, $resultSetPrototype);
        }
        return $this->tableGateway;
    }

    /**
     *
     * @param TableGateway $tableGateway
     */
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        if (! $this->adapter) {
            try {
                $this->adapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
            } catch (\Exception $e) {
                $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            }
        }
        return $this->adapter;
    }

    /**
     *
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /*
     * (non-PHPdoc) @see \SamFramework\src\Core\AutoBuildInterface::getInstance()
     */
    public static function getInstance(ServiceLocatorInterface $sl)
    {
        $currentClass = get_called_class();
        return new $currentClass();
    }
}

