<?php
namespace SamFramework\Form\Element;

use Zend\Form\Element\Textarea as ZFETextarea;

class Textarea extends ZFETextarea
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'textarea',
        'class' => 'form-control'
    );
}

?>