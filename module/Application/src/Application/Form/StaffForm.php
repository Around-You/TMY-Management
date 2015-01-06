<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaffForm extends Form
{
    /**
     *
     * @param ServiceLocatorInterface $sl
     * @return Application\Form\MemberForm
     */
    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\StaffForm');
    }

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user_form');
    }

    public function init()
    {
        $this->setAttribute('class', 'form-horizontal');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));
        $this->add(array(
            'name' => 'login_name',
            'type' => 'Text',
            'options' => array(
                'label' => '登录名'
            ),
            'attributes' => array(
                'tabindex'=>1
            )
        ));
        $this->add(array(
            'name' => 'staff_name',
            'type' => 'Text',
            'options' => array(
                'label' => '员工姓名'
            ),
            'attributes' => array(
                'tabindex'=>2
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => '登陆密码'
            ),
            'attributes' => array(
                'tabindex'=>3
            )
        ));
        $this->add(array(
            'name' => 'confirm_password',
            'type' => 'Password',
            'options' => array(
                'label' => '确认登陆密码'
            ),
            'attributes' => array(
                'tabindex'=>4
            )
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => '联系地址'
            ),
            'attributes' => array(
                'tabindex'=>5
            )
        ));
        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => '联系电话'
            ),
            'attributes' => array(
                'tabindex'=>6
            )
        ));
        $this->add(array(
            'name' => 'role',
            'type' => 'Select',
            'options' => array(
                'label' => '角色',
                'value_options' => array(
                    '管理员' => '管理员',
                    '店长' => '店长',
                    '店员' => '店员'
                )
            ),
            'attributes' => array(
                'tabindex'=>7
            )
        ));
        $this->add(array(
            'name' => 'fake_log_discount',
            'type' => 'Text',
            'options' => array(
                'label' => '流水折扣'
            ),
            'attributes' => array(
                'tabindex'=>8
            )
        ));



        $this->add(array(
            'name' => 'submit',
            'type' => 'submitButton',
            'options' => array(
                'label' => '保存'
            )
        ));
//         $this->add(array(
//             'name' => 'cancel',
//             'type' => 'cancelButton',
//             'options' => array(
//                 'label' => '重设'
//             )
//         ));
    }



}