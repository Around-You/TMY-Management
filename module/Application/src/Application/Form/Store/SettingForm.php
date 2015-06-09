<?php
namespace Application\Form\Store;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingForm extends Form
{
    /**
     *
     * @param ServiceLocatorInterface $sl
     * @return Application\Form\MemberForm
     */
    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Application\Form\Store\SettingForm');
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
            'name' => 'fake_sale_amount',
            'type' => 'Text',
            'options' => array(
                'label' => '当日销售额'
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