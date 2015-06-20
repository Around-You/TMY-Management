<?php
namespace Application\Model\Report;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;

/**
 *
 * @property string dateString
 *
 */
class DailyReport extends AbstractModel
{
    public $id = 0;
    public $date = NULL;
    public $member_count = 0;
    public $sale_count = 0;
    public $sale_amount = 0;
    public $fake_sale_amount = 0;


    /**
     * exclude fields to save
     */
    protected $exclude = array('dateString');

    public function exchangeArray( array $array )
    {
        $this->id = (isset( $array['id'] )) ? $array['id'] : $this->id;
        $this->date = (isset( $array['date'] )) ? $array['date'] : $this->date;
        $this->member_count = (isset( $array['member_count'] )) ? $array['member_count'] : $this->member_count;
        $this->sale_count = (isset( $array['sale_count'] )) ? $array['sale_count'] : $this->sale_count;
        $this->sale_amount = (isset( $array['sale_amount'] )) ? $array['sale_amount'] : $this->sale_amount;
        $this->fake_sale_amount = (isset( $array['fake_sale_amount'] )) ? $array['fake_sale_amount'] : $this->fake_sale_amount;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'date' => $this->date,
            'member_count' => $this->member_count,
            'sale_count' => $this->sale_count,
            'sale_amount' => $this->sale_amount,
            'fake_sale_amount' => $this->fake_sale_amount,
            'dateString' => $this->dateString
        );
        return $data;
    }

    public function getInputFilter()
    {
        if ( !$this->inputFilter ) {
            $inputFilter = new InputFilter();

            $inputFilter->add(
                array(
                    'name' => 'fake_sale_amount',
                    'filters' => array(
                        array(
                            'name' => 'StripTags'
                        ),
                        array(
                            'name' => 'StringTrim'
                        )
                    )

                ) );
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getDateString(){
        return date('Y-m-d',strtotime($this->date));
    }
}

