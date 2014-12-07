<?php
return array(
    'view_helpers' => array(
        'invokables' => array(
            'pageHeader' => 'Components\Layout\View\Helper\Header',
            'form' => 'Components\Form\View\Helper\Form',
            'formRow' => 'Components\Form\View\Helper\FormRow',
            'formbutton' => 'Components\Form\View\Helper\FormButton',
            'formActions' => 'Components\Form\View\Helper\FormActions',
            'FormElementErrors' => 'Components\Form\View\Helper\FormElementErrors',
            'formwysiwyg' => 'Components\Form\View\Helper\FormWysiwyg',
            'flashMessenger' => 'Components\Layout\View\Helper\FlashMessenger',
            'sidebar' => 'Components\Layout\View\Helper\Sidebar',
        ),
    ),

    'form_elements' => array(
        'invokables' => array(
            'submitButton' => 'Components\Form\Element\SubmitButton',
            'cancelButton' => 'Components\Form\Element\CancelButton',
            'WYSIWYG' => 'Components\Form\Element\WYSIWYG',
            'text' => 'Components\Form\Element\Text',
            'select' => 'Components\Form\Element\Select'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'components/layout/flashmessager' => __DIR__ . '/../view/layout/flashmessager.phtml',
        ),
    ),
);