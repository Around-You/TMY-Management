<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;

class User extends AbstractModel
{

    public $id = 0;

    public $store_id = 0;

    public $username = '';

    public $realname = '';

    public $email = '';

    public $password = '';

    public $address = '';

    public $phone = '';

    public $role = '';

    public $enable = '';

    public $create_time = '';

    public $update_time = '';

    public $fake_log_discount = '';

    protected $exclude = array(
        'create_time',
    );


    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->store_id = (isset($array['store_id'])) ? $array['store_id'] : $this->store_id;
        $this->username = (isset($array['username'])) ? $array['username'] : $this->username;
        $this->realname = (isset($array['realname'])) ? $array['realname'] : $this->realname;
        $this->email = (isset($array['email'])) ? $array['email'] : $this->email;
        $this->password = (isset($array['password'])) ? $array['password'] : $this->password;
        $this->address = (isset($array['address'])) ? $array['address'] : $this->address;
        $this->phone = (isset($array['phone'])) ? $array['phone'] : $this->phone;
        $this->role = (isset($array['role'])) ? $array['role'] : $this->role;
        $this->enable = (isset($array['enable'])) ? $array['enable'] : $this->enable;
        $this->create_time = (isset($array['create_time'])) ? $array['create_time'] : $this->create_time;
        $this->update_time = (isset($array['update_time'])) ? $array['update_time'] : $this->update_time;
        $this->fake_log_discount = (isset($array['fake_log_discount'])) ? $array['fake_log_discount'] : $this->fake_log_discount;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'store_id' => $this->store_id,
            'username' => $this->username,
            'realname' => $this->realname,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
            'phone' => $this->phone,
            'role' => $this->role,
            'enable' => $this->enable,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'fake_log_discount' => $this->fake_log_discount
        )
        ;
        return $data;
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

