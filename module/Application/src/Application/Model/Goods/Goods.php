<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;

/**
 *
 * @property string priceString
 * @property string descString
 *
 */
class Goods extends AbstractModel
{

    const GOODS_TYPE_COUNT = '次卡';

    const GOODS_TYPE_TIME = '时间卡';

    const GOODS_TYPE_NORMAL = '商品';

    public $id = 0;

    public $title = '';

    public $code = '';

    public $description = '';

    public $user_id = 0;

    public $category_id = '';

    public $category_name = '';

    public $create_time = '';

    public $update_time = '';

    public $price = 0;

    public $cost = 0;

    public $quantity = 0;

    public $date_range = '';

    public $count = 0;

    public $type = '';

    protected $exclude = array(
        "priceString",
        'descString',
        'category_name',
        'desc'
    );

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
        $this->code = (isset($array['code'])) ? $array['code'] : $this->code;
        $this->description = (isset($array['description'])) ? $array['description'] : $this->description;
        $this->category_id = (isset($array['category_id'])) ? $array['category_id'] : $this->category_id;
        $this->category_name = (isset($array['category_name'])) ? $array['category_name'] : $this->category_name;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
        $this->price = (isset($array['price'])) ? $array['price'] : $this->price;
        $this->cost = (isset($array['cost'])) ? $array['cost'] : $this->cost;
        $this->quantity = (isset($array['quantity'])) ? $array['quantity'] : $this->quantity;
        $this->date_range = (isset($array['date_range'])) ? $array['date_range'] : $this->date_range;
        $this->count = (isset($array['count'])) ? $array['count'] : $this->count;
        $this->type = (isset($array['type'])) ? $array['type'] : $this->type;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'price' => $this->price,
            'priceString' => $this->getPriceString(),
            'cost' => $this->cost,
            'quantity' => $this->quantity,
            'date_range' => $this->date_range,
            'count' => $this->count,
            'type' => $this->type,

            'desc' => $this->descString,
            'update_time' => $this->update_time
        );
        return $data;
    }

    public function getDescString()
    {
        $string = '';
        switch ($this->type) {
            case self::GOODS_TYPE_COUNT:
                $string = $this->count . '次';
                break;
            case self::GOODS_TYPE_TIME:
                $string = $this->date_range;
                break;
            case self::GOODS_TYPE_NORMAL:
                $string = '库存: ' . $this->quantity . '件';
                break;
            default:
        }
        return $string;
    }

    public function getPriceString()
    {
        return '¥ ' . round($this->price, 2);
    }

    /**
     *
     * @return boolean
     */
    public function isVirtual()
    {
        if ($this->type == "次卡" || $this->type == "时间卡") {
            return true;
        } else {
            return false;
        }
    }
}

