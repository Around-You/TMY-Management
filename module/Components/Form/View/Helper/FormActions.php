<?php
namespace Components\Form\View\Helper;


use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormInput;
use Zend\Form\ElementInterface;

class FormActions extends AbstractHelper
{
    /**
     * Form element helper instance
     *
     * @var FormElement
     */
    protected $elementHelper;

    /**
     * Generate an opening form tag
     *
     * @param  null|FormInterface $form
     * @return string
     */
    public function openTag()
    {
        return '';
    }

    /**
     * Generate a closing form tag
     *
     * @return string
     */
    public function closeTag()
    {
        return '';
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|FormInput
     */
    public function __invoke()
    {
        if ( func_num_args() > 0 ){
            $args = func_get_args();
            $content = '';
            foreach ( $args as $element) {
                if ($element instanceof ElementInterface) {
                	$content .= $this->renderElement( $element );
                }
            }

            $openTag = $this->openTag();
            $closeTag = $this->closeTag();


            return $openTag . $content . $closeTag;
        }else{
            return $this;
        }

    }

    public function renderElement( ElementInterface $element ){
        $elementHelper = $this->getElementHelper();
        return $elementHelper->render($element);
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

        return $this->elementHelper;
    }

}

