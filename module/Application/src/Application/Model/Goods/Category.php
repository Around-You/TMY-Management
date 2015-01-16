<?php
namespace Application\Model\Goods;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;

class Category extends AbstractModel
{

    public $id = 0;

    public $store_id = 1;

    public $title = '';

    public $enable = 1;

    public $parent_id = 0;

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->enable = (isset($array['enable'])) ? $array['enable'] : $this->enable;
        $this->title = (isset($array['title'])) ? $array['title'] : $this->title;
        // $this->parent_id = (isset($array['parent_id'])) ? $array['parent_id'] : $this->parent_id;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'enable' => $this->enable
        // 'parent_id' => $this->parent_id
                );
        return $data;
    }
}

