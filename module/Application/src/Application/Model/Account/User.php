<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModel;
use Zend\InputFilter\InputFilter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthDbTableAdapter;
use Zend\Authentication\Result;
use Application\Form\LoginForm;
use SamFramework\Core\App;

class User extends AbstractModel
{

    public $id = 0;

    public $name = '';

    public $email = '';

    public $password = '';

    public $create_time = '';

    public $update_time = '';

    public $weibo_token = '';

    public $weibo_name = '';

    public $weibo_token_expiry = '';

    public $weibo_id = '';





    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->name = (isset($array['name'])) ? $array['name'] : $this->name;
        $this->email = (isset($array['email'])) ? $array['email'] : $this->email;
        $this->password = (isset($array['password'])) ? $array['password'] : $this->password;
        $this->weibo_token = (isset($array['weibo_token'])) ? $array['weibo_token'] : $this->weibo_token;
        $this->weibo_name  = (isset($array['weibo_name'])) ? $array['weibo_name'] : $this->weibo_name;
        $this->weibo_token_expiry  = (isset($array['weibo_token_expiry'])) ? $array['weibo_token_expiry'] : $this->weibo_token_expiry;
        $this->weibo_id  = (isset($array['weibo_id'])) ? $array['weibo_id'] : $this->weibo_id;
    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'weibo_token' => $this->weibo_token,
            'weibo_name' => $this->weibo_name,
            'weibo_token_expiry' => $this->weibo_token_expiry,
            'weibo_id' => $this->weibo_id,
        );
        return $data;
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'email',
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

