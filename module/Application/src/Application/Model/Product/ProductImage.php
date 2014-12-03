<?php

namespace Application\Model\Product;

use SamFramework\Model\AbstractModel;

class ProductImage extends AbstractModel
{

    public $id = 0;

    public $name = '';

    public $product_id = 0;

    public $file_path = '';

    public $uri = '';

    public $thumbnail_uri = '';

    public $is_default = 0;


    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : null;
        $this->name = (isset($array['name'])) ? $array['name'] : null;
        $this->product_id = (isset($array['product_id'])) ? $array['product_id'] : null;
        $this->file_path = (isset($array['file_path'])) ? $array['file_path'] : null;
        $this->uri = (isset($array['uri'])) ? $array['uri'] : null;
        $this->thumbnail_uri = (isset($array['thumbnail_uri'])) ? $array['thumbnail_uri'] : null;
        $this->is_default = (isset($array['is_default'])) ? $array['is_default'] : null;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'product_id' => $this->product_id,
            'file_path' => $this->file_path,
            'uri' => $this->uri,
            'thumbnail_uri' => $this->thumbnail_uri,
            'is_default' => $this->is_default,
        );
        return $data;
    }


}

