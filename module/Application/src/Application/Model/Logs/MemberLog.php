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

    /**
     * exclude fields to save
     */
    protected $exclude = array('create_time');

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->action = (isset($array['action'])) ? $array['action'] : $this->action;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'action' => $this->action,
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'goods_id' => $this->goods_id,
            'create_time' => $this->create_time
        );
        return $data;
    }
}

