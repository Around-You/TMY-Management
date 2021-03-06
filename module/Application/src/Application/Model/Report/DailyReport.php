<?php
namespace Application\Model\Report;

use SamFramework\Model\AbstractModel;

/**
 *
 * @property string priceString
 *
 */
class DailyReport extends AbstractModel
{

    public $id = 0;

    public $date = NULL;

    public $member_count = 0;

    public $sale_count = 0;


    /**
     * exclude fields to save
     */
    protected $exclude = array();

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->date = (isset($array['date'])) ? $array['date'] : $this->date;
        $this->member_count = (isset($array['member_count'])) ? $array['member_count'] : $this->member_count;
        $this->sale_count = (isset($array['sale_count'])) ? $array['sale_count'] : $this->sale_count;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'date' => $this->date,
            'member_count' => $this->member_count,
            'sale_count' => $this->sale_count,
        );
        return $data;
    }
}

