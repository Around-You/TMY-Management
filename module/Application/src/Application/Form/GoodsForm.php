<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoodsForm extends Form
{

    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\GoodsForm');
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
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => '商品名称'
            )
        ));

        $this->add(array(
            'name' => 'code',
            'type' => 'Text',
            'options' => array(
                'label' => '商品编码'
            )
        ));

        $this->add(array(
            'name' => 'category_id',
            'type' => 'Select',
            'options' => array(
                'label' => '商品类别',
            )
        ));

        $this->add(array(
            'name' => 'type',
            'type' => 'Select',
            'options' => array(
                'label' => '商品类型',
                'value_options' => array(
                    '时间卡' => '时间卡',
                    '次卡' => '次卡',
                    '普通商品' => '普通商品'
                )
            )
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'Text',
            'options' => array(
                'label' => '售价'
            )
        ));
        $this->add(array(
            'name' => 'cost',
            'type' => 'Text',
            'options' => array(
                'label' => '成本价'
            )
        ));
        $this->add(array(
            'name' => 'cost',
            'type' => 'Text',
            'options' => array(
                'label' => '成本价'
            )
        ));
        $this->add(array(
            'name' => 'quantity',
            'type' => 'Text',
            'options' => array(
                'label' => '库存数量'
            )
        ));
        $this->add(array(
            'name' => 'count',
            'type' => 'Text',
            'options' => array(
                'label' => '次卡-次数'
            )
        ));

        $this->add(array(
            'name' => 'date_range',
            'type' => 'Select',
            'options' => array(
                'label' => '时间卡-时间',
                'value_options' => array(
                    '月卡' => '月卡',
                    '季卡' => '季卡',
                    '年卡' => '年卡'
                )
            )
        ));
        $this->add(array(
            'name' => 'cost',
            'type' => 'Text',
            'options' => array(
                'label' => '成本价'
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

    public function setCategories($categories)
    {
        $categoriesArr = array();
        foreach ($categories as $category) {
            $categoriesArr[$category->id] = $category->title;
        }
        $this->get('category_id')->setValueOptions($categoriesArr);
    }
}