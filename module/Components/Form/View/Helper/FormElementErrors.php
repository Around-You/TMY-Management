<?php
namespace Components\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as ZVHFormElementErrors;

class FormElementErrors extends ZVHFormElementErrors
{
    /**@+
     * @var string Templates for the open/close/separators for message tags
    */
    protected $messageCloseString     = '</div >';
    protected $messageOpenFormat      = '<div%s>';
    protected $messageSeparatorString = '';
    /**@-*/

    /**
     * @var array Default attributes for the open format tag
     */
    protected $attributes = array(
    	'class' => 'help-block'
    );

}

