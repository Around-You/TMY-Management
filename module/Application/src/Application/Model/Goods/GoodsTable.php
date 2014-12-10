<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModelMapper;
use Application\Model\Product\Product;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class GoodsTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    protected $tableName = 'goods';

    protected $modelClassName = 'Application\\Model\\Goods\\Goods';

    public function buildSqlSelect(Select $select)
    {
        $select->join('category', 'category.id=category_id', array(
            'category_name' => 'title'
        ));
        // if($this->currentStoreId){
        // $select->where('product.store_id='.$this->currentStoreId);
        // }
    }

    public function getFetchAllCounts()
    {
        $select = $this->getTableGateway()->getSql()->select();
        $this->buildSqlSelect($select);
        $select->columns(array(
            new Expression('count(goods.id) as rownum')
        ));
        $statement = $this->getTableGateway()->getSql()->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return $row['rownum'];
    }

    public function fetchAll($offset = 0, $limit = 10)
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

    public function getProduct($id)
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

        // Get Product Images
        $productImageTable = $this->getProductImageTable();
        $rowset = $productImageTable->getProductImagesByProductId($id);
        $arrProductImages = array();
        foreach ($rowset as $productImage) {
            $arrProductImages[] = $productImage;
        }
        $row->product_images = $arrProductImages;
        return $row;
    }

    public function deleteProduct($id)
    {
        $this->tableGateway->delete(array(
            'id' => (int) $id
        ));
    }

    public function saveProduct(Product $product)
    {
        $tableGateway = $this->getTableGateway();
        $product->update_time = date('YmdHis');
        $product->user_id = $this->currentStoreId;
        $data = $product->getArrayCopyForSave();
        $id = (int) $product->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $product->id = $this->getTableGateway()->getLastInsertValue();

            $productImageTable = $this->getProductImageTable();
            $productImageTable->updateProductId($product->id, $product->product_images);
        } else {
            if ($this->getProduct($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }

        return $product;
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
}

