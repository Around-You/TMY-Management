<?php
namespace Components\Form\View\Helper;


use Zend\Form\View\Helper\Form as ZVHForm;
use Zend\Form\FormInterface;
class Form extends ZVHForm
{
    /**
     * Generate an opening form tag
     *
     * @param  null|FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form = null)
    {
        $attributes = array(
            'action' => '',
            'method' => 'get',
        );

        if ($form instanceof FormInterface) {
            $formAttributes = $form->getAttributes();
            if (!array_key_exists('id', $formAttributes) && array_key_exists('name', $formAttributes)) {
                $formAttributes['id'] = $formAttributes['name'];
            }
            $attributes = array_merge($attributes, $formAttributes);
        }

        $tag = sprintf(' <div class="row"><div class="col-xs-12"><form %s>', $this->createAttributesString($attributes));

        return $tag;
    }

    /**
     * Generate a closing form tag
     *
     * @return string
     */
    public function closeTag()
    {
        return '</form> </div></div>';
    }

}

