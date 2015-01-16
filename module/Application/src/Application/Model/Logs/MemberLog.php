<?php
namespace Application\Model\Logs;

use SamFramework\Model\AbstractModel;

/**
 *
 * @property string priceString
 *
 */
class MemberLog extends AbstractModel
{

    public $id = 0;

    public $action = '';

    public $member_id = 0;

    public $user_id = 0;

    public $goods_id = 0;

    public $create_time = '';

    public $goods_title = '';

    public $goods_code = '';

    public $staff_name = '';

    /**
     * exclude fields to save
     */
    protected $exclude = array(
        'create_time',
        'goods_code',
        'goods_title',
        'staff_name'
    );

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->action = (isset($array['action'])) ? $array['action'] : $this->action;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->goods_code = (isset($array['goods_code'])) ? $array['goods_code'] : $this->goods_code;
        $this->goods_title = (isset($array['goods_title'])) ? $array['goods_title'] : $this->goods_title;
        $this->staff_name = (isset($array['staff_name'])) ? $array['staff_name'] : $this->staff_name;
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
            'goods_code' => $this->goods_code,
            'goods_title' => $this->goods_title,
            'staff_name' => $this->staff_name
        );
        return $data;
    }
}

