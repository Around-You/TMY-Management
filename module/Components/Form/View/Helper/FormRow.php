<?php
namespace Components\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormRow as ZVHFormRow;
use Zend\Form\LabelAwareInterface;
use Zend\Form\Element\MonthSelect;
use Zend\Form\Element\Button;
use Zend\Form\View\Helper\FormElement;


class FormRow extends ZVHFormRow
{
    protected $warpClass = 'form-group';
    protected $inputErrorClass = 'has-error';

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param  ElementInterface $element
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $escapeHtmlHelper    = $this->getEscapeHtmlHelper();
        $labelHelper         = $this->getLabelHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();

        $label           = $element->getLabel();
        $inputErrorClass = $this->getInputErrorClass();

        if (isset($label) && '' !== $label) {
            // Translate the label
            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }
        }
        $warpClass = $this->warpClass;
        // Does this element have errors ?
        if (count($element->getMessages()) > 0 && !empty($inputErrorClass)) {
//             $classAttributes = ($element->hasAttribute('class') ? $element->getAttribute('class') . ' ' : '');
//             $classAttributes = $classAttributes . $inputErrorClass;

            $warpClass .= ' ' . $inputErrorClass;
//             $element->setAttribute('class', $classAttributes);
        }

        if ($this->partial) {
            $vars = array(
                'element'           => $element,
                'label'             => $label,
                'labelAttributes'   => $this->labelAttributes,
                'labelPosition'     => $this->labelPosition,
                'renderErrors'      => $this->renderErrors,
            );

            return $this->view->render($this->partial, $vars);
        }

        if ($this->renderErrors) {
            $elementErrors = $elementErrorsHelper->render($element);
        }



        $elementString = $this->renderElement( $element );

        // hidden elements do not need a <label> -https://github.com/zendframework/zf2/issues/5607
        $type = $element->getAttribute('type');
        if (isset($label) && '' !== $label && $type !== 'hidden') {


            if ($element instanceof LabelAwareInterface) {
                $this->setLabelAttributes( $element->getLabelAttributes() );
            }

            if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }


            // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
            // labels. The semantic way is to group them inside a fieldset
//             if ($type === 'multi_checkbox' || $type === 'radio' || $element instanceof MonthSelect ) {
//                 $markup = sprintf(
//                     '<fieldset><legend>%s</legend>%s</fieldset>',
//                     $label,
//                     $elementString);
//                 if ($this->renderErrors) {
//                     $markup .= $elementErrors;
//                 }
//             } else {
                // Ensure element and label will be separated if element has an `id`-attribute.
                // If element has label option `always_wrap` it will be nested in any case.
                if ($element->hasAttribute('id')
                    && ($element instanceof LabelAwareInterface && !$element->getLabelOption('always_wrap'))
                ) {
                    $labelOpen = '';
                    $labelClose = '';
                    $label = $labelHelper($element);
                } else {
                    $labelOpen  = $labelHelper->openTag($this->getLabelAttributes());
                    $labelClose = $labelHelper->closeTag();
                }

                if ($label !== '' && (!$element->hasAttribute('id'))
                    || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
                ) {
                    $label = '<span>' . $label . '</span>';
                }

                // Button element is a special case, because label is always rendered inside it
                if ($element instanceof Button) {
                    $labelOpen = $labelClose = $label = '';
                }

                switch ($this->labelPosition) {
                	case self::LABEL_PREPEND:
                	    $markup = '<div class="' . $warpClass . '">' . $labelOpen . $label . $labelClose . ' <div>' . $elementString . ($this->renderErrors?$elementErrors:'') . "</div>" . "</div>";
                	    break;
                	case self::LABEL_APPEND:
                	default:
                	    $markup = $labelOpen . $elementString . $label . $labelClose;
                	    break;
                }
//             }


        } else {
            if ($this->renderErrors) {
                $markup = $elementString . $elementErrors;
            } else {
                $markup = $elementString;
            }
        }

        return $markup;
    }

    public function renderElement( ElementInterface $element ){
        $elementHelper = $this->getElementHelper();
        return $elementHelper->render($element);
    }

    /**
     * Get the attributes for the row label
     *
     * @return array
     */
    public function getLabelAttributes()
    {

        $labelClass = 'control-label no-padding-right';
        $classAttributes = isset( $this->labelAttributes['class'] ) ? $this->labelAttributes['class'] . ' ' : '';
        $this->labelAttributes['class'] = $classAttributes . $labelClass;

        return $this->labelAttributes;
    }

    /**
     * Retrieve the FormElement helper
     *
     * @return FormElement
     */
    protected function getElementHelper()
    {
        if ($this->elementHelper) {
            return $this->elementHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementHelper = $this->view->plugin('form_element');
        }

        if (!$this->elementHelper instanceof FormElement) {
            $this->elementHelper = new FormElement();
        }
        $this->elementHelper->addClass('Components\Form\Element\WYSIWYG', 'formwysiwyg');
        return $this->elementHelper;
    }


}

