<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\Account\UserAuthenticator;
use Zend\Authentication\Result;
use Application\Form\LoginForm;
use SamFramework\Core\App;

class AccountController extends AbstractActionController
{

    public function loginAction()
    {
        $this->layout('layout/login');
        $form = LoginForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $authenticator =  $this->getServiceLocator()->get('Application\Model\Account\UserAuthenticator');
                $result = $authenticator->doPasswordAuth($form->get('username')->getValue(), $form->get('password')->getValue());
                switch ($result->getCode()) {

                    case Result::SUCCESS:
                        /** do stuff for successful authentication **/
                        $this->redirect()->toUrl('/dashboard');
                        break;

                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity **/
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential **/
                    default:
                        /** do stuff for other failure **/
                        $form->get( 'username')->setMessages( array('用户名或密码不正确'));
                        break;
                }
                foreach ($result->getMessages() as $message) {
                    echo "$message\n";
                }
            }
        }

        return array(
            'form' => $form
        );
    }


    public function logoutAction()
    {
        App::clearUser();
        App::getUser(); // For goto login page
    }


}
