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

    public $username = '';

    public $realname = '';

    public $email = '';

    public $password = '';

    public $create_time = '';

    public $update_time = '';







    public function exchangeArray(array $array)
    {
        $this->id = (isset($array['id'])) ? $array['id'] : $this->id;
        $this->username = (isset($array['username'])) ? $array['username'] : $this->username;
        $this->realname = (isset($array['realname'])) ? $array['realname'] : $this->realname;
        $this->email = (isset($array['email'])) ? $array['email'] : $this->email;
        $this->password = (isset($array['password'])) ? $array['password'] : $this->password;

    }

    public function getArrayCopy()
    {
        $data = array(
            'id' => $this->id,
            'username' => $this->username,
            'realname' => $this->realname,
            'email' => $this->email,
            'password' => $this->password,

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

