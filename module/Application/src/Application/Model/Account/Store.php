<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;

class Store extends AbstractModel
{

    public $id = 0;

    public $title = '';

    public $user_id = '';



    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->title = (isset($array['title'])) ? $array['title'] : $this->title;
        $this->user_id = (isset($array['user_id'])) ? $array['user_id'] : $this->user_id;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
        );
        return $data;
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

