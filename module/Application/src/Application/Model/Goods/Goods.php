<?php
namespace Application\Model;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;
/**
 *
 * @property string priceString
 *
 */
class Goods extends AbstractModel
{

    public $id = 0;

    public $title = '';

    public $description = '';

    public $user_id = 0;

    public $category_id = '';

    public $category_name = '';

    public $create_time = '';

    public $update_time = '';

    public $price = '';

    public $unit = '';

    public $product_images = '';

    public $recommend = 0;

    public $product_thumbnail = '';

    public $countOfOrders = 0;




    protected $exclude = array( 'product_images');

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->title = (isset($array['title'])) ? $array['title'] : $this->title;
        $this->description = (isset($array['description'])) ? $array['description'] : $this->description;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
        $this->category_id = (isset($array['category_id'])) ? $array['category_id'] : $this->category_id;
        $this->category_name = (isset($array['category_name'])) ? $array['category_name'] : $this->category_name;
        $this->product_thumbnail = (isset($array['product_thumbnail'])) ? $array['product_thumbnail'] : $this->product_thumbnail;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
        $this->product_images = (isset($array['product_images'])) ? $array['product_images'] : $this->product_images;
        $this->price = (isset($array['price'])) ? $array['price'] : $this->price;
        $this->unit = (isset($array['unit'])) ? $array['unit'] : $this->unit;
        $this->recommend = (isset($array['recommend'])) ? $array['recommend'] : $this->recommend;
        $this->countOfOrders = (isset($array['countOfOrders'])) ? $array['countOfOrders'] : $this->countOfOrders;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'update_time' => $this->update_time,
            'product_images' => $this->product_images,
            'price' => $this->price,
            'unit' => $this->unit,
            'recommend' => $this->recommend,
        );
        return $data;
    }

    public function getPriceString()
    {
        return $this->price . ' / ' . $this->unit;
    }


}

