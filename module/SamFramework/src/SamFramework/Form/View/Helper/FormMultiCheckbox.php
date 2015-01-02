<?php
namespace SamFramework\Form\View\Helper;

use Zend\Form\View\Helper\FormMultiCheckbox as ZFVFormMultiCheckbox;
use Zend\Form\Element\MultiCheckbox as MultiCheckboxElement;
use Zend\Form\LabelAwareInterface;

class FormMultiCheckbox extends ZFVFormMultiCheckbox
{

    /**
     * The attributes applied to option label
     *
     * @var array
     */
    protected $labelAttributes = array(
        'class' => 'checkbox-inline'
    );

    /**
     * Render options
     *
     * @param MultiCheckboxElement $element
     * @param array $options
     * @param array $selectedOptions
     * @param array $attributes
     * @return string
     */
    protected function renderOptions(MultiCheckboxElement $element, array $options, array $selectedOptions, array $attributes)
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper = $this->getLabelHelper();
        $labelClose = $labelHelper->closeTag();
        $labelPosition = $this->getLabelPosition();
        $globalLabelAttributes = array();
        $closingBracket = $this->getInlineClosingBracket();

        if ($element instanceof LabelAwareInterface) {
            $globalLabelAttributes = $element->getLabelAttributes();
        }

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $combinedMarkup = array();
        $count = 0;

        foreach ($options as $key => $optionSpec) {
            $count ++;
            if ($count > 1 && array_key_exists('id', $attributes)) {
                unset($attributes['id']);
            }

            $value = '';
            $label = '';
            $inputAttributes = $attributes;
            $labelAttributes = $globalLabelAttributes;
            $selected = isset($inputAttributes['selected']) && $inputAttributes['type'] != 'radio' && $inputAttributes['selected'] != false ? true : false;
            $disabled = isset($inputAttributes['disabled']) && $inputAttributes['disabled'] != false ? true : false;

            if (is_scalar($optionSpec)) {
                $optionSpec = array(
                    'label' => $optionSpec,
                    'value' => $key
                );
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }
            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }
            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }
            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }
            if (isset($optionSpec['label_attributes'])) {
                $labelAttributes = (isset($labelAttributes)) ? array_merge($labelAttributes, $optionSpec['label_attributes']) : $optionSpec['label_attributes'];
            }
            if (isset($optionSpec['attributes'])) {
                $inputAttributes = array_merge($inputAttributes, $optionSpec['attributes']);
            }

            if (in_array($value, $selectedOptions)) {
                $selected = true;
            }

            $inputAttributes['value'] = $value;
            $inputAttributes['checked'] = $selected;
            $inputAttributes['disabled'] = $disabled;

            $input = sprintf('<input %s%s', $this->createAttributesString($inputAttributes), $closingBracket);

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate($label, $this->getTranslatorTextDomain());
            }

            if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }
            $label = sprintf('<span class="lbl">%s</span>', $label);
            $labelOpen = $labelHelper->openTag($labelAttributes);
            $template = $labelOpen . '%s%s' . $labelClose;
            switch ($labelPosition) {
                case self::LABEL_PREPEND:
                    $markup = sprintf($template, $label, $input);
                    break;
                case self::LABEL_APPEND:
                default:
                    $markup = sprintf($template, $input, $label);
                    break;
            }
            $combinedMarkup[] = $markup;
        }

        return implode($this->getSeparator(), $combinedMarkup);
    }
}
