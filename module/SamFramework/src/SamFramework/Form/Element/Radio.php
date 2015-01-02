<?php
namespace SamFramework\Form\Element;

use Zend\Form\Element\Radio as ZFERadio;

class Radio extends ZFERadio
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'radio',
        'display-inline' => true
    );
}

