<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;
use SamFramework\Core\App;

class Staff extends AbstractModel
{

    public $id = 0;

    public $store_id = 1;

    public $login_name = '';

    public $staff_name = '';

    public $email = '';

    public $password = '';

    public $confirm_password = '';

    public $address = '';

    public $phone = '';

    public $role = '';

    public $enable = 1;

    public $create_time = NULL;

    public $update_time = NULL;

    public $fake_log_discount = 1;

    protected $exclude = array(
        'create_time'
    );

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'login_name',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Db\NoRecordExists',
                        'options' => array(
                            'table' => 'staff',
                            'field' => 'login_name',
                            'adapter' => App::getReadDBAdapter()
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'password',
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
            $inputFilter->add(array(
                'name' => 'confirm_password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\Identical',
                        'options' => array(
                            'token' => 'password'
                        )
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
        $this->store_id = (isset($array['store_id'])) ? $array['store_id'] : $this->store_id;
        $this->login_name = (isset($array['login_name'])) ? $array['login_name'] : $this->login_name;
        $this->staff_name = (isset($array['staff_name'])) ? $array['staff_name'] : $this->staff_name;
        $this->email = (isset($array['email'])) ? $array['email'] : $this->email;
        $this->password = (isset($array['password'])) ? $array['password'] : $this->password;
        $this->confirm_password = (isset($array['confirm_password'])) ? $array['confirm_password'] : $this->confirm_password;
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
            'login_name' => $this->login_name,
            'staff_name' => $this->staff_name,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
            'phone' => $this->phone,
            'role' => $this->role,
            'enable' => $this->enable,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'fake_log_discount' => $this->fake_log_discount
        );
        return $data;
    }

    public function encryptPassword($password = NULL, $salt = NULL)
    {
        if ($password == NULL) {
            $password = $this->password;
        }
        if ($salt == NULL) {
            $salt = $this->login_name;
        }
        return md5($password . $salt);
    }
}

