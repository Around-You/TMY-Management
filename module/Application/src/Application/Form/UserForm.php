<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserForm extends Form
{
    /**
     *
     * @param ServiceLocatorInterface $sl
     * @return Application\Form\MemberForm
     */
    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\UserForm');
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
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => '登录名'
            )
        ));
        $this->add(array(
            'name' => 'realname',
            'type' => 'Text',
            'options' => array(
                'label' => '员工姓名'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => '登陆密码'
            )
        ));
        $this->add(array(
            'name' => 'confirm_password',
            'type' => 'Password',
            'options' => array(
                'label' => '确认登陆密码'
            )
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => '联系地址'
            )
        ));
        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => '联系电话'
            )
        ));
        $this->add(array(
            'name' => 'role',
            'type' => 'Select',
            'options' => array(
                'label' => '角色',
                'value_options' => array(
                    'admin' => '管理员',
                    'master' => '店长',
                    'staff' => '店员'
                )
            )
        ));
        $this->add(array(
            'name' => 'fake_log_discount',
            'type' => 'Text',
            'options' => array(
                'label' => '流水折扣'
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