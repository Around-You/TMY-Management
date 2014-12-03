<?php
namespace Mobile\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class BuyerForm extends Form
{

    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Mobile\Form\BuyerForm');
    }


    public function init()
    {

        $this->add(array(
            'name' => 'weixin',
            'type' => 'Text',
            'options' => array(
                'label' => 'weixin'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'submitButton',
            'options' => array(
                'label' => 'Submit',
            )
        ));
    }


}