<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'SamFramework\Core\AutoBuildAbstractFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'pageHeader' => 'SamFramework\Layout\View\Helper\Header',
            'form' => 'SamFramework\Form\View\Helper\Form',
            'formRow' => 'SamFramework\Form\View\Helper\FormRow',
            'formbutton' => 'SamFramework\Form\View\Helper\FormButton',
            'formActions' => 'SamFramework\Form\View\Helper\FormActions',
            'formelement' => 'SamFramework\Form\View\Helper\FormElement',
            'formelementerrors'     => 'SamFramework\Form\View\Helper\FormElementErrors',
            'formmulticheckbox' => 'SamFramework\Form\View\Helper\FormMultiCheckbox',
            'formradio' => 'SamFramework\Form\View\Helper\FormRadio',
            'formwysiwyg' => 'SamFramework\Form\View\Helper\FormWysiwyg',
            'formdatepicker' => 'SamFramework\Form\View\Helper\FormDatePicker',
            'flashMessenger' => 'SamFramework\Layout\View\Helper\FlashMessenger',
            'sidebar' => 'SamFramework\Layout\View\Helper\Sidebar',
            'dataTable' => 'SamFramework\Layout\View\Helper\DataTable',
            'formcheckbox' => 'SamFramework\Form\View\Helper\FormCheckbox',
            'formchosen' => 'SamFramework\Form\View\Helper\FormChosen',
        )
    ),

    'form_elements' => array(
        'invokables' => array(
            'submitButton' => 'SamFramework\Form\Element\SubmitButton',
            'cancelButton' => 'SamFramework\Form\Element\CancelButton',
            'WYSIWYG' => 'SamFramework\Form\Element\WYSIWYG',
            'password' => 'SamFramework\Form\Element\Password',
            'text' => 'SamFramework\Form\Element\Text',
            'textarea' => 'SamFramework\Form\Element\Textarea',
            'select' => 'SamFramework\Form\Element\Select',
            'radio' => 'SamFramework\Form\Element\Radio',
            'datepicker' => 'SamFramework\Form\Element\DatePicker',
            'chosen' => 'SamFramework\Form\Element\Chosen',
            'checkbox' => 'SamFramework\Form\Element\Checkbox',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'components/layout/flashmessager' => __DIR__ . '/../view/layout/flashmessager.phtml'
        )
    )
);