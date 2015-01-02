<?php
namespace SamFramework\Form\Element;

class DatePicker extends Text
{

    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'text',
        'class' => 'form-control date-picker',
        'data-date-format' => 'yyyy-mm-dd'
    );
}
