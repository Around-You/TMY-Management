<?php

namespace SamFramework\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement as ZVHFormElement;

class FormElement extends ZVHFormElement
{


    /**
     * Instance map to view helper
     *
     * @var array
     */
    protected $classMap = array(
        'Zend\Form\Element\Button'         => 'formbutton',
        'Zend\Form\Element\Captcha'        => 'formcaptcha',
        'Zend\Form\Element\Csrf'           => 'formhidden',
        'Zend\Form\Element\Collection'     => 'formcollection',
        'Zend\Form\Element\DateTimeSelect' => 'formdatetimeselect',
        'Zend\Form\Element\DateSelect'     => 'formdateselect',
        'Zend\Form\Element\MonthSelect'    => 'formmonthselect',
        'SamFramework\Form\Element\DatePicker' => 'formdatepicker',
        'SamFramework\Form\Element\Chosen' => 'formchosen'
    );

    /**
     * Type map to view helper
     *
     * @var array
     */
    protected $typeMap = array(
        'checkbox'       => 'formcheckbox',
        'color'          => 'formcolor',
        'date'           => 'formdate',
        'datepicker'     => 'formdatepicker',
        'datetime'       => 'formdatetime',
        'datetime-local' => 'formdatetimelocal',
        'email'          => 'formemail',
        'file'           => 'formfile',
        'hidden'         => 'formhidden',
        'image'          => 'formimage',
        'month'          => 'formmonth',
        'multi_checkbox' => 'formmulticheckbox',
        'number'         => 'formnumber',
        'password'       => 'formpassword',
        'radio'          => 'formradio',
        'range'          => 'formrange',
        'reset'          => 'formreset',
        'search'         => 'formsearch',
        'select'         => 'formselect',
        'submit'         => 'formsubmit',
        'tel'            => 'formtel',
        'text'           => 'formtext',
        'textarea'       => 'formtextarea',
        'time'           => 'formtime',
        'url'            => 'formurl',
        'week'           => 'formweek',
    );


}
