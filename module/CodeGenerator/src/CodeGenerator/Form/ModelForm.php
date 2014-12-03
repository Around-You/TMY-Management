<?php
namespace CodeGenerator\Form;

use Zend\Form\Form;

class ModelForm extends Form
{

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('album');

        $this->add(array(
            'name' => 'table_name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Table Name'
            )
        ));
        $this->add(array(
            'name' => 'model_class',
            'type' => 'Text',
            'options' => array(
                'label' => 'Model Class'
            )
        ));
        $this->add(array(
            'name' => 'namespace',
            'type' => 'Text',
            'options' => array(
                'label' => 'Namespace'
            )
        ));
        $this->add(array(
            'name' => 'path',
            'type' => 'Text',
            'options' => array(
                'label' => 'File Path'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Perview',
                'id' => 'submitbutton'
            )
        ));
    }

    public function getInputFilter()
    {
		return array();
    }
}