<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class MemberGoodsForm extends Form
{

    /**
     *
     * @param ServiceLocatorInterface $sl
     *
     * @return MemberGoodsForm
     */
    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\MemberGoodsForm');
    }

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('goods_form');
    }

    public function init()
    {
        $this->setAttribute('id', 'buy_goods_form');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal');
        $this->add(array(
            'name' => 'member_id',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));
        $this->add(array(
            'name' => 'goods_id',
            'type' => 'Chosen',
            'options' => array(
                'label' => '商品卡名称'
            ),
            'attributes' => array(
                'id' => 'goods_id',
                'data-placeholder' => '请选择商品'
            )
        ));

        $this->add(array(
            'name' => 'checkbox-start',
            'type' => 'Checkbox',
            'options' => array(
                'label' => '现在开卡'
            ),
            'attributes' => array(
                'id' => 'checkbox-start',
                'checked' => 'checked'
            )
        ));

        $this->add(array(
            'name' => 'start_date',
            'type' => 'DatePicker',
            'options' => array(
                'label' => '开始时间'
            ),
            'attributes' => array()
        ));
        $this->add(array(
            'name' => 'end_date',
            'type' => 'DatePicker',
            'options' => array(
                'label' => '结束时间'
            )
        ));
        $this->add(array(
            'name' => 'count',
            'type' => 'Text',
            'options' => array(
                'label' => '次数'
            ),
            'attributes' => array(
                'id' => 'form-count',
                'value' => 0
            )
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Textarea',
            'options' => array(
                'label' => '备注'
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

    public function setGoods($goods)
    {
        array_unshift($goods, array(
            'label' => '',
            'value' => ''
        ));
        $this->get('goods_id')->setValueOptions($goods);
    }
}