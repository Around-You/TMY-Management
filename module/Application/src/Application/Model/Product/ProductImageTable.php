<?php
namespace Application\Model\Product;

use SamFramework\Model\AbstractModelMapper;
use Application\Model\Product\ProductImage;

class ProductImageTable extends AbstractModelMapper
{

    protected $tableName = 'product_image';

    protected $modelClassName = 'Application\\Model\\Product\\ProductImage';

    public function getProductImage()
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

    public function deleteProductImage($id)
    {
        $tableGateway = $this->getTableGateway();
        return $tableGateway->delete(array(
            'id' => (int) $id
        ));
    }

    public function saveProductImage(ProductImage $productImage)
    {
        $tableGateway = $this->getTableGateway();
        $data = $productImage->getArrayCopy();
        $id = (int) $productImage->id;
        if ($id == 0) {
            $images = $this->getDefaultImage($productImage->product_id);
            if ($images->count() == 0) {
            	$productImage->is_default = 1;
            	$data = $productImage->getArrayCopyForSave();
            }
            $tableGateway->insert($data);
            $productImage->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getProductImage($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $productImage;
    }

    public function updateProductId($productId, $images)
    {
        if (! empty($images) && is_array($images)) {
            $this->getTableGateway()->update(array(
                'product_id' => $productId
            ), array(
                'id' => $images
            ));
        }
    }

    public function getProductImagesByProductId($productId)
    {
        $tableGateway = $this->getTableGateway();
        $productId = (int) $productId;
        $rowset = $tableGateway->select(array(
            'product_id' => $productId
        ));
        return $rowset;
    }

    public function setImageAsDefault($imageId, $productId)
    {
        $tableGateway = $this->getTableGateway();

        $this->getTableGateway()->update(array(
            'is_default' => 0
        ), array(
            'product_id' => $productId
        ));

        return $this->getTableGateway()->update(array(
            'is_default' => 1
        ), array(
            'id' => $imageId
        ));
    }

    public function getDefaultImage($productId)
    {
        $tableGateway = $this->getTableGateway();
        $productId = (int) $productId;
        $rowset = $tableGateway->select(array(
            'product_id' => $productId,
            'is_default' => 1
        ));
        return $rowset;
    }
}

