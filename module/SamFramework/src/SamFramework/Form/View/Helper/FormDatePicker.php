<?php
namespace SamFramework\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception\DomainException;
use Zend\View\Helper\InlineScript;
use Zend\View\Helper\BasePath;
use Zend\Form\View\Helper\FormText;
use Zend\View\Helper\HeadLink;

class FormDatePicker extends FormText
{
    protected $inlineScriptHelper;
    protected $headLinkHelper;
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

    protected function getHeadLinkHelper()
    {
        if ($this->headLinkHelper) {
            return $this->headLinkHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->headLinkHelper = $this->view->plugin('headLink');
        }

        if (!$this->headLinkHelper instanceof HeadLink) {
            $this->headLinkHelper = new HeadLink();
        }

        return $this->headLinkHelper;
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
     * Render a form <input> element with date picker from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes          = $element->getAttributes();
        $attributes['name']  = $name;
        $attributes['type']  = $this->getType($element);
        $attributes['value'] = $element->getValue();

        $this->renderScript($name);

        return sprintf(
            '<div class="input-group"><input %s%s',
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket()
        );
    }

    /**
     * Get the closing bracket for an inline tag
     *
     * Closes as either "/>" for XHTML doctypes or ">" otherwise.
     *
     * @return string
     */
    public function getInlineClosingBracket()
    {
        $doctypeHelper = $this->getDoctypeHelper();
        if ($doctypeHelper->isXhtml()) {
            return '/><span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span></div>';
        }
        return '><span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span></div>';
    }

    protected function renderScript($name){
        $inlineScriptHelper = $this->getInlineScriptHelper();
        $headLinkHelper = $this->getHeadLinkHelper();
        $headLinkHelper->appendStylesheet($this->getView()->basePath('css/datepicker.css'));
        $inlineScriptHelper->appendFile($this->getView()->basePath('js/date-time/bootstrap-datepicker.min.js'));
        $inlineScriptHelper->captureStart();
        echo <<<JS
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		}).next().on('click', function(){
			$(this).prev().focus();
		});
JS;
        $inlineScriptHelper->captureEnd();
    }
}

