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

    public $phone = 0;

    public $address = '';

    public $id_type = '';

    public $id_code = '';

    public $point = '';

    public $parent_name = '';

    public $perpaid = '';

    public $create_at_store = 0;

    public $create_by_user = 0;

    public $create_time = '';

    public $update_time = '';




    protected $exclude = array( 'product_images');

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
        $this->perpaid = (isset($array['perpaid'])) ? $array['perpaid'] : $this->perpaid;
        $this->create_at_store = (isset($array['create_at_store'])) ? $array['create_at_store'] : $this->create_at_store;
        $this->create_by_user = (isset($array['create_by_user'])) ? $array['create_by_user'] : $this->create_by_user;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
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
            'update_time' => $this->update_time,
            'point' => $this->point,
            'parent_name' => $this->parent_name,
            'perpaid' => $this->perpaid,
            'create_at_store' => $this->create_at_store,
            'create_by_user' => $this->create_by_user,
            'create_at_store' => $this->create_at_store,
            'create_by_user' => $this->create_by_user,
        );
        return $data;
    }




}

