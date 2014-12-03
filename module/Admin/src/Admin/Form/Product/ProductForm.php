<?php
namespace Admin\Form\Product;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductForm extends Form
{

    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Admin\Form\Product\ProductForm');
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
            'name' => 'product_images',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'product-title',
            ),
            'options' => array(
                'label' => '商品名称'
            )
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Wysiwyg',
            'options' => array(
                'label' => '商品说明'
            )
        ));
        $this->add(array(
            'name' => 'category_id',
            'type' => 'Select',
            'options' => array(
                'label' => '商品类型',
            )
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'Text',
            'options' => array(
                'label' => '商品价格'
            )
        ));
        $this->add(array(
            'name' => 'unit',
            'type' => 'Text',
            'options' => array(
                'label' => '价格单位'
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