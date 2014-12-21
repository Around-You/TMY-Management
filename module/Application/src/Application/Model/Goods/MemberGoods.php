<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModel;

/**
 *
 * @property string priceString
 *
 */
class MemberGoods extends AbstractModel
{

    public $id = 0;

    public $start_date = '';

    public $end_date = '';

    public $member_id = 0;

    public $count = 0;

    public $goods_id = 0;

    public $create_time = '';

    /**
     * exclude fields to save
     */
    protected $exclude = array(
        'create_time'
    );

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->start_date = (isset($array['start_date'])) ? $array['start_date'] : $this->start_date;
        $this->end_date = (isset($array['end_date'])) ? $array['end_date'] : $this->end_date;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->count = (isset($array['count'])) ? $array['count'] : $this->count;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'member_id' => $this->member_id,
            'count' => $this->count,
            'goods_id' => $this->goods_id,
            'create_time' => $this->create_time
        );
        return $data;
    }
}

