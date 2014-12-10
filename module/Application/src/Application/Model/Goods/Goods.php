<?php
namespace Application\Model\Goods;

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

//     public $description = '';

    public $user_id = 0;

    public $category_id = '';

    public $category_name = '';

    public $create_time = '';

    public $update_time = '';

    public $price = '';

    public $cost = '';

    public $quantity = '';

    public $date_range = '';

    public $count = '';

    protected $exclude = array();

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
//         $this->description = (isset($array['description'])) ? $array['description'] : $this->description;
        $this->category_id = (isset($array['category_id'])) ? $array['category_id'] : $this->category_id;
        $this->category_name = (isset($array['category_name'])) ? $array['category_name'] : $this->category_name;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
        $this->price = (isset($array['price'])) ? $array['price'] : $this->price;
        $this->cost = (isset($array['cost'])) ? $array['cost'] : $this->cost;
        $this->quantity = (isset($array['quantity'])) ? $array['quantity'] : $this->quantity;
        $this->date_range  = (isset($array['date_range '])) ? $array['date_range '] : $this->date_range ;
        $this->count  = (isset($array['count '])) ? $array['count '] : $this->count ;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'cost' => $this->cost,
            'quantity' => $this->quantity,
            'date_range' => $this->date_range,
            'count' => $this->count,

            'update_time' => $this->update_time,
        );
        return $data;
    }

    public function getPriceString()
    {
        return '¥ '.round($this->price,2);
    }
}

