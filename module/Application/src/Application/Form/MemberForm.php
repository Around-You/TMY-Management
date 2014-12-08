<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class MemberForm extends Form
{

    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\MemberForm');
    }

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('product_form');
    }

    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => '会员姓名'
            )
        ));
        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'options' => array(
                'label' => '会员卡号'
            )
        ));
        $this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => '手机/电话'
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
            'name' => 'id_type',
            'type' => 'Select',
            'options' => array(
                'label' => '证件类型'
            )
        ));
        $this->add(array(
            'name' => 'id_code',
            'type' => 'Text',
            'options' => array(
                'label' => '证件号码'
            )
        ));
        $this->add(array(
            'name' => 'parent_name',
            'type' => 'Text',
            'options' => array(
                'label' => '监护人姓名'
            )
        ));
        $this->add(array(
            'name' => 'prepaid',
            'type' => 'Text',
            'options' => array(
                'label' => '预存金额'
            )
        ));
        $this->add(array(
            'name' => 'goods',
            'type' => 'Select',
            'options' => array(
                'label' => '会员类别'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Select',
            'options' => array(
                'label' => '密码'
            )
        ));
        $this->add(array(
            'name' => 'goods',
            'type' => 'Select',
            'options' => array(
                'label' => '确认密码'
            )
        ));
        $this->add(array(
            'name' => 'goods',
            'type' => 'Select',
            'options' => array(
                'label' => '推荐人'
            )
        ));
        $this->add(array(
            'name' => 'gender',
            'type' => 'Radio',
            'options' => array(
                'label' => '性别',
                'value_options' => array(
                    '男' => '男',
                    '女' => '女'
                )
            )
        ));
        $this->add(array(
            'name' => 'dob',
            'type' => 'Select',
            'options' => array(
                'label' => '生日'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'submitButton',
            'options' => array(
                'label' => '保存'
            )
        ));
        $this->add(array(
            'name' => 'cancel',
            'type' => 'cancelButton',
            'options' => array(
                'label' => '重设'
            )
        ));
    }

    protected function getServiceLocator()
    {
        return $this->getFormFactory()
            ->getFormElementManager()
            ->getServiceLocator();
    }
}