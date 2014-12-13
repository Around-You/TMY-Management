<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            'pageHeader' => 'Components\Layout\View\Helper\Header',
            'form' => 'Components\Form\View\Helper\Form',
            'formRow' => 'Components\Form\View\Helper\FormRow',
            'formbutton' => 'Components\Form\View\Helper\FormButton',
            'formActions' => 'Components\Form\View\Helper\FormActions',
            'formelement' => 'Components\Form\View\Helper\FormElement',
            'formelementerrors'     => 'Components\Form\View\Helper\FormElementErrors',
            'formmulticheckbox' => 'Components\Form\View\Helper\FormMultiCheckbox',
            'formradio' => 'Components\Form\View\Helper\FormRadio',
            'formwysiwyg' => 'Components\Form\View\Helper\FormWysiwyg',
            'formdatepicker' => 'Components\Form\View\Helper\FormDatePicker',
            'flashMessenger' => 'Components\Layout\View\Helper\FlashMessenger',
            'sidebar' => 'Components\Layout\View\Helper\Sidebar'
        )
    ),

    'form_elements' => array(
        'invokables' => array(
            'submitButton' => 'Components\Form\Element\SubmitButton',
            'cancelButton' => 'Components\Form\Element\CancelButton',
            'WYSIWYG' => 'Components\Form\Element\WYSIWYG',
            'text' => 'Components\Form\Element\Text',
            'select' => 'Components\Form\Element\Select',
            'radio' => 'Components\Form\Element\Radio',
            'datepicker' => 'Components\Form\Element\DatePicker',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'components/layout/flashmessager' => __DIR__ . '/../view/layout/flashmessager.phtml'
        )
    )
);