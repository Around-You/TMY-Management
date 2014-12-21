<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModel;

/**
 *
 * @property string description
 * @property string status
 *
 */
class MemberGoods extends AbstractModel
{

    public $id = 0;

    public $start_date = NULL;

    public $end_date = NULL;

    public $member_id = 0;

    public $member_name = '';

    public $member_code = '';

    public $count = 0;

    public $goods_id = 0;

    public $create_time = '';

    public $goods_title = '';

    public $goods_type = '';

    protected $_status = NULL;

    /**
     * exclude fields to save
     */
    protected $exclude = array(
        'create_time',
        'goods_title',
        'description',
        'member_name',
        'member_code',
        'goods_type',
        'status'
    );

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->start_date = (isset($array['start_date'])) ? $array['start_date'] : $this->start_date;
        $this->end_date = (isset($array['end_date'])) ? $array['end_date'] : $this->end_date;
        $this->member_id = (isset($array['member_id'])) ? $array['member_id'] : $this->member_id;
        $this->member_name = (isset($array['member_name'])) ? $array['member_name'] : $this->member_name;
        $this->member_code = (isset($array['member_code'])) ? $array['member_code'] : $this->member_code;
        $this->count = (isset($array['count'])) ? $array['count'] : $this->count;
        $this->goods_id = (isset($array['goods_id'])) ? $array['goods_id'] : $this->goods_id;
        $this->goods_title = (isset($array['goods_title'])) ? $array['goods_title'] : $this->goods_title;
        $this->goods_type = (isset($array['goods_type'])) ? $array['goods_type'] : $this->goods_type;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'member_id' => $this->member_id,
            'member_name' => $this->member_name,
            'member_code' => $this->member_code,
            'count' => $this->count,
            'goods_id' => $this->goods_id,
            'goods_title' => $this->goods_title,
            'goods_type' => $this->goods_type,
            'description' => $this->getDescription(),
            'create_time' => $this->create_time,
            'status' => $this->status
        );
        return $data;
    }

    public function getStatus()
    {
        if (! $this->_status) {}
        return $this->_status;
    }

    public function getDescription()
    {
        $description = '';
        switch ($this->goods_type) {
            case '时间卡':
                $description = '起止时间为 ' . $this->start_date . ' 至 ' . $this->end_date;
                break;
            case '次卡':
                $description = '剩余次数为 ' . $this->count;
                break;
        }
        return $description;
    }

    public function setGoods(Goods $goods, $startDate)
    {
        $this->goods_id = $goods->id;
        if ($goods->type == '次卡') {
            $this->count = $goods->count;
        } else {
            $this->start_date = strtotime('midnight', $startDate);
            switch ($goods->date_range) {
                case "1年":
                    $this->end_date = strtotime('+1 year', $this->start_date);
                    break;
                case "月卡":
                case "1个月":
                    $this->end_date = strtotime('+1 month', $this->start_date);
                    break;
                case "3个月":
                    $this->end_date = strtotime('+3 month', $this->start_date);
                    break;
                case "6个月":
                    $this->end_date = strtotime('+6 month', $this->start_date);
                    break;
            }
            $this->start_date = date('Y-m-d H:i:s', $this->start_date);
            $this->end_date = date('Y-m-d H:i:s', $this->end_date);
        }
    }
}

