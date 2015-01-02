<?php
namespace SamFramework\Form\Element;

use Zend\Form\Element\Select as ZFESelect;

class Select extends ZFESelect
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'select',
        'class' => 'form-control'
    );
}

