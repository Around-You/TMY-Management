<?php
namespace SamFramework\Form\Element;

use Zend\Form\Element\Password as ZFEPassword;

class Password extends ZFEPassword
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'password',
        'class' => 'form-control'
    );
}

