<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilter;

class LoginForm extends Form
{

    public static function getInstance(ServiceLocatorInterface $sl)
    {
        return $sl->get('FormElementManager')->get('\Admin\Form\LoginForm');
    }


    public function init()
    {

        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'UserName'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password'
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

    public function getInputFilter()
    {
        if (! $this->filter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));

            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));


            $this->filter = $inputFilter;
        }

        return $this->filter;
    }
}