<?php
namespace Components\Form\Element;

use Zend\Form\Element\Button;

class SubmitButton extends Button implements ElementWithIconInterface
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'submit',
        'class' => 'btn btn-info'
    );


	/* (non-PHPdoc)
     * @see \Components\Form\Element\ElementWithIconInterface::getDefaultIcon()
     */
    public function getDefaultIcon()
    {
        // TODO Auto-generated method stub
        return ElementWithIconInterface::ICON_SUBMIT;
    }



}

