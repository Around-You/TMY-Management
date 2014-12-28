<?php
namespace Application\Model\Logs;

use SamFramework\Model\AbstractModel;

/**
 *
 * @property string priceString
 *
 */
class SellLog extends AbstractModel
{

    public $id = 0;

    public $action = '';

    public $member_id = NULL;

    public $user_id = 0;

    public $goods_id = 0;

    public $create_time = NULL;

    public $quantity = 0;

    public $price = 0;

    public $member_name = '';

    public $member_code = '';

    public $goods_title = '';

    public $user_name = '';

    /**
     * exclude fields to save
     */
    protected $exclude = array(
        'create_time',
        'member_name',
        'member_code',
        'goods_title',
        'user_name'
    );

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->action = (isset($array['action'])) ? $array['action'] : $this->action;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->quantity = (isset($array['quantity'])) ? $array['quantity'] : $this->quantity;
        $this->price = (isset($array['price'])) ? $array['price'] : $this->price;
        $this->member_name = (isset($array['member_name'])) ? $array['member_name'] : $this->member_name;
        $this->member_code = (isset($array['member_code'])) ? $array['member_code'] : $this->member_code;
        $this->goods_title = (isset($array['goods_title'])) ? $array['goods_title'] : $this->goods_title;
        $this->user_name = (isset($array['user_name'])) ? $array['user_name'] : $this->user_name;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'action' => $this->action,
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'goods_id' => $this->goods_id,
            'create_time' => $this->create_time,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'member_name' => $this->member_name,
            'member_code' => $this->member_code,
            'goods_title' => $this->goods_title,
            'user_name' => $this->user_name
        );
        return $data;
    }
}

