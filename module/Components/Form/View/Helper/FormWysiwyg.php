<?php
namespace Components\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\Exception\DomainException;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Helper\InlineScript;
use Zend\View\Helper\BasePath;

class FormWysiwyg extends AbstractHelper
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
     * Attributes valid for the input tag
     *
     * @var array
     */
    protected $validTagAttributes = array(
        'autofocus' => true,
        'cols' => true,
        'dirname' => true,
        'disabled' => true,
        'form' => true,
        'maxlength' => true,
        'name' => true,
        'placeholder' => true,
        'readonly' => true,
        'required' => true,
        'rows' => true,
        'wrap' => true
    );

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
     * Render a form <textarea> element from the provided $element
     *
     * @param ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if (empty($name) && $name !== 0) {
            throw new DomainException(sprintf('%s requires that the element has an assigned name; none discovered', __METHOD__));
        }

        $this->renderScript($name);

        $attributes = $element->getAttributes();
        $content = (string) $element->getValue();

        return sprintf('<div %s>%s</div>' . PHP_EOL . '<input type="hidden" name="' . $name . '" />', $this->createAttributesString($attributes), $content);
    }

    protected function renderScript($name){
        $inlineScriptHelper = $this->getInlineScriptHelper();
        $inlineScriptHelper->appendFile($this->getView()->basePath('js/uncompressed/wysiwyg.js'));
        $inlineScriptHelper->captureStart();
echo <<<JS
$('.wysiwyg-editor').parents("form").on('submit', function(){
	$('input[name=$name]' , this).val($('.wysiwyg-editor').html());
});
JS;
        $inlineScriptHelper->captureEnd();
    }
}

