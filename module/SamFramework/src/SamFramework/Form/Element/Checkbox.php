<?php
namespace SamFramework\Form\Element;

use Zend\Form\Element\Checkbox as ZFECheckbox;

class Checkbox extends ZFECheckbox
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'checkbox',
        'class' => 'ace'
    );
    /**
     * @var bool
     */
    protected $useHiddenElement = FALSE;
}

