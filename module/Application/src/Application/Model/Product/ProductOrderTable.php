<?php
namespace Application\Model\Product;

use SamFramework\Model\AbstractModelMapper;
use Application\Model\Product\Product;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class ProductOrderTable extends AbstractModelMapper
{
    public $currentUserId = 0;

    protected $tableName = 'product';

    protected $projectImageTable;

    protected $modelClassName = 'Application\\Model\\Product\\Product';

    public function getProductImageTable()
    {
        if (! $this->projectImageTable) {
            $this->projectImageTable = $this->getServiceLocator()->get('Application\Model\Product\ProductImageTable');
        }

        return $this->projectImageTable;
    }

    public function buildSqlSelect(Select $select){
        $select->join('category', 'category.id=category_id', array(
            'category_name' => 'title'
        ));
        $select->join('product_image', new Expression("product.id=product_id and is_default=1"), array(
            'product_thumbnail' => 'thumbnail_uri'
        ), Select::JOIN_LEFT);
        $select->join('order', 'product.id=order.product_id', array('quantity'));
        $select->group('product.id');
        if($this->currentUserId){
            $select->where('product.user_id='.$this->currentUserId);
        }
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
            $select->columns(array(
                'id',
                'title',
                'price',
                'unit',
                'recommend',
                'countOfOrders' => new Expression('sum(quantity)')
            ));
            $table->buildSqlSelect($select);
            $select->offset($offset)
                ->limit($limit);
        });
        return $resultSet;
    }


}

