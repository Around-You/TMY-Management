<?php
namespace Application\Model\Member;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;
/**
 *
 *
 */
class Member extends AbstractModel
{

    public $id = 0;

    public $name = '';

    public $code = '';

    public $phone = '';

    public $address = '';

    public $id_type = '';

    public $id_code = '';

    public $point = 0;

    public $parent_name = '';

    public $gender = '男';

    public $dob= null;

    public $created_at_store = 0;

    public $created_by_user = 0;

    public $created_time = '';

    public $update_time = '';

    public $enable = 1;

    public $goods = 0;

    public $description = '';

    public $referral = NULL;

    public $referral_staff_name = '';


    public function __construct(){
        $this->created_time = date('Y-m-d');
    }


    protected $exclude = array( 'referral_staff_name' );

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'name',
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
        $this->name = (isset($array['name'])) ? $array['name'] : $this->name;
        $this->code = (isset($array['code'])) ? $array['code'] : $this->code;
        $this->phone = (isset($array['phone'])) ? $array['phone'] : $this->phone;
        $this->address = (isset($array['address'])) ? $array['address'] : $this->address;
        $this->id_type = (isset($array['id_type'])) ? $array['id_type'] : $this->id_type;
        $this->id_code = (isset($array['id_code'])) ? $array['id_code'] : $this->id_code;
        $this->point = (isset($array['point'])) ? $array['point'] : $this->point;
        $this->parent_name = (isset($array['parent_name'])) ? $array['parent_name'] : $this->parent_name;
        $this->gender  = (isset($array['gender'])) ? $array['gender'] : $this->gender ;
        $this->dob  = (isset($array['dob'])) ? $array['dob'] : $this->dob ;
        $this->goods  = (isset($array['goods'])) ? $array['goods'] : $this->goods ;
        $this->created_at_store = (isset($array['created_at_store'])) ? $array['created_at_store'] : $this->created_at_store;
        $this->created_by_user = (isset($array['created_by_user'])) ? $array['created_by_user'] : $this->created_by_user;
        $this->created_time = (isset($array['created_time'])) ? $array['created_time'] : $this->created_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
        $this->enable = (isset($array['enable'])) ? $array['enable'] : $this->enable;
        $this->description = (isset($array['description'])) ? $array['description'] : $this->description;
        $this->referral = (isset($array['referral'])) ? $array['referral'] : $this->referral;
        $this->referral_staff_name = (isset($array['referral_staff_name'])) ? $array['referral_staff_name'] : $this->referral_staff_name;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'code' => $this->code,
            'address' => $this->address,
            'id_type' => $this->id_type,
            'id_code' => $this->id_code,
            'point' => $this->point,
            'parent_name' => $this->parent_name,
            'gender' => $this->gender,
            'dob' => !empty($this->dob)?$this->dob:NULL,
            'created_at_store' => $this->created_at_store,
            'created_by_user' => $this->created_by_user,
            'created_time' => $this->created_time,
            'update_time' => $this->update_time,
            'enable'=> $this->enable,
            'description'=> $this->description,
            'referral'=> $this->referral,
            'referral_staff_name' => $this->referral_staff_name
        );
        return $data;
    }




}

