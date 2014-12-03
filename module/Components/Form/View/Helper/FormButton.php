<?php
namespace Components\Form\View\Helper;


use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormButton  as ZVHFormButton;
use Zend\Validator\IsInstanceOf;
use Components\Form\Element\ButtonWithIcon;
use Components\Form\Element\ElementWithIconInterface;
class FormButton extends ZVHFormButton
{

    /**
     * Render a form <button> element from the provided $element,
     * using content from $buttonContent or the element's "label" attribute
     *
     * @param  ElementInterface $element
     * @param  null|string $buttonContent
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element, $buttonContent = null)
    {
        $openTag = $this->openTag($element);

        $icon = $this->renderIcon($element);

        if (null === $buttonContent) {
            $buttonContent = $element->getLabel();
            if (null === $buttonContent) {
                throw new Exception\DomainException(sprintf(
                    '%s expects either button content as the second argument, ' .
                        'or that the element provided has a label value; neither found',
                    __METHOD__
                ));
            }

            if (null !== ($translator = $this->getTranslator())) {
                $buttonContent = $translator->translate(
                    $buttonContent, $this->getTranslatorTextDomain()
                );
            }
        }

        if (! $element instanceof LabelAwareInterface || ! $element->getLabelOption('disable_html_escape')) {
            $escapeHtmlHelper = $this->getEscapeHtmlHelper();
            $buttonContent = $escapeHtmlHelper($buttonContent);
        }

        return $openTag . $icon . $buttonContent . $this->closeTag();
    }

    protected function renderIcon( ElementInterface $element )
    {

        $iconHtml = '';
        if ( $element instanceof ElementWithIconInterface ) {
            $icon = $element->getOption('icon');
            if ( !$icon ) {
                $icon = $element->getDefaultIcon();
            }
            $iconHtml = '<i class="' . $icon . ' bigger-110"></i>';
        }
        return $iconHtml;

    }


}

