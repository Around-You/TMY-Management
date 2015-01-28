<?php
namespace SamFramework\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception\DomainException;
use Zend\View\Helper\InlineScript;
use Zend\View\Helper\BasePath;
use Zend\Form\View\Helper\FormSelect;

class FormChosen extends FormSelect
{
    protected $inlineScriptHelper;
    protected $basePathHelper;

    protected function getInlineScriptHelper()
    {
        if ($this->inlineScriptHelper) {
            return $this->inlineScriptHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->inlineScriptHelper = $this->view->plugin('inlinescript');
        }

        if (!$this->inlineScriptHelper instanceof InlineScript) {
            $this->inlineScriptHelper = new InlineScript();
        }

        return $this->inlineScriptHelper;
    }

    protected function getBasePathHelper()
    {
        if ($this->basePathHelper) {
            return $this->basePathHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->basePathHelper = $this->view->plugin('basepath');
        }

        if (!$this->basePathHelper instanceof BasePath) {
            $this->basePathHelper = new BasePath();
        }

        return $this->inlineScriptHelper;
    }



    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param ElementInterface|null $element
     * @return string FormTextarea
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render a form <select> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof SelectElement) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Zend\Form\Element\Select',
                __METHOD__
            ));
        }

        $name   = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $options = $element->getValueOptions();

        if (($emptyOption = $element->getEmptyOption()) !== null) {
            $options = array('' => $emptyOption) + $options;
        }

        $attributes = $element->getAttributes();
        $value      = $this->validateMultiValue($element->getValue(), $attributes);

        $attributes['name'] = $name;
        if (array_key_exists('multiple', $attributes) && $attributes['multiple']) {
            $attributes['name'] .= '[]';
        }
        $this->validTagAttributes = $this->validSelectAttributes;

        $rendered = sprintf(
            '<select %s>%s</select>',
            $this->createAttributesString($attributes),
            $this->renderOptions($options, $value)
        );

        // Render hidden element
        $useHiddenElement = method_exists($element, 'useHiddenElement')
        && method_exists($element, 'getUnselectedValue')
        && $element->useHiddenElement();

        if ($useHiddenElement) {
            $rendered = $this->renderHiddenElement($element) . $rendered;
        }

        $this->renderScript($name);

        return $rendered;
    }

    protected function renderScript($name){
        $inlineScriptHelper = $this->getInlineScriptHelper();
        $inlineScriptHelper->appendFile($this->getView()->basePath('js/chosen.jquery.js'));
        $inlineScriptHelper->captureStart();
echo <<<JS
$('input[name=$name]').chosen({
	allow_single_deselect : true
});
JS;
        $inlineScriptHelper->captureEnd();
    }
}

