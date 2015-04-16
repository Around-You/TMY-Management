<?php
namespace Application\Model\Logs;

use SamFramework\Model\AbstractModel;

/**
 *
 *
 */
class MemberLog extends AbstractModel
{
    const MEMBER_LOG_ACTION_TYPE_PRINT = '打印';
    const MEMBER_LOG_ACTION_TYPE_USE = '扣次/使用';

    public $id = 0;

    public $action = '';

    public $member_id = 0;

    public $member_goods_id = NULL;

    public $member_name = '';

    public $member_code = '';

    public $user_id = 0;

    public $goods_id = 0;

    public $create_time = '';

    public $goods_title = '';

    public $goods_code = '';

    public $staff_name = '';

    public $count = 1;

    public $is_deleted = 0;

    public $delete_time = NULL;

    /**
     * exclude fields to save
     */
    protected $exclude = array(
        'create_time',
        'goods_code',
        'goods_title',
        'staff_name',
        'member_code',
        'member_name'
    );

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->action = (isset($array['action'])) ? $array['action'] : $this->action;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->member_code = (isset($array['member_code'])) ? $array['member_code'] : $this->member_code;
        $this->member_name = (isset($array['member_name'])) ? $array['member_name'] : $this->member_name;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->goods_code = (isset($array['goods_code'])) ? $array['goods_code'] : $this->goods_code;
        $this->goods_title = (isset($array['goods_title'])) ? $array['goods_title'] : $this->goods_title;
        $this->staff_name = (isset($array['staff_name'])) ? $array['staff_name'] : $this->staff_name;
        $this->is_deleted = (isset($array['is_deleted'])) ? $array['is_deleted'] : $this->is_deleted;
        $this->delete_time = (isset($array['delete_time'])) ? $array['delete_time'] : $this->delete_time;
        $this->count = (isset($array['count'])) ? $array['count'] : $this->count;
        $this->member_goods_id = (isset($array['member_goods_id'])) ? $array['member_goods_id'] : $this->member_goods_id;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'action' => $this->action,
            'member_id' => $this->member_id,
            'member_code' => $this->member_code,
            'member_name' => $this->member_name,
            'user_id' => $this->user_id,
            'goods_id' => $this->goods_id,
            'create_time' => $this->create_time,
            'goods_code' => $this->goods_code,
            'goods_title' => $this->goods_title,
            'staff_name' => $this->staff_name,
            'count' => $this->count,
            'is_deleted' => $this->is_deleted,
            'delete_time' => $this->delete_time,
            'member_goods_id' => $this->member_goods_id
        );
        return $data;
    }
}

