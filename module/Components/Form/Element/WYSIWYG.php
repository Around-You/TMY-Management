<?php
namespace Components\Form\Element;

use Zend\Form\Element;

class WYSIWYG extends Element
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'wysiwyg',
        'class' => 'wysiwyg-editor',
    );
}
