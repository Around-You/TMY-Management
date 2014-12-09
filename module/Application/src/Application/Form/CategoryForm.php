<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategoryForm extends Form
{

    public static function getInstance( ServiceLocatorInterface $sl){
        return $sl->get('FormElementManager')->get('\Application\Form\CategoryForm');
    }

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('category_form');
    }

    public function init(){
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'value' => 0
            )
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => '商品类别名称'
            )
        ));
        $this->add(array(
            'name' => 'type',
            'type' => 'Select',
            'options' => array(
                'label' => '商品类别类型',
                'value_options' => array(
                    '年卡' => '年卡',
                    '季卡' => '季卡',
                    '月卡' => '月卡',
                    '次卡' => '次卡',
                    '普通商品'=>'普通商品'

                )
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'submitButton',
            'options' => array(
                'label' => '保存',
            )
        ));
        $this->add(array(
            'name' => 'cancel',
            'type' => 'cancelButton',
            'options' => array(
                'label' => '重设',
            )
        ));
    }

}