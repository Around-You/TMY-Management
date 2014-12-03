<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\Member;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthDbTableAdapter;
use Zend\Authentication\Result;
use Admin\Form\LoginForm;
use SamFramework\Core\App;

class AccountController extends AbstractActionController
{

    public function loginAction()
    {
        $this->layout('admin/layout/login');
        $form = LoginForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $result = $this->doAuthenticate($form->get('username')->getValue(), $form->get('password')->getValue());
                switch ($result->getCode()) {

                    case Result::SUCCESS:
                        /** do stuff for successful authentication **/
                        $this->redirect()->toUrl('/admin/product');
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

    protected function doAuthenticate($username, $password)
    {
        $auth = new AuthenticationService();

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new AuthDbTableAdapter($dbAdapter, 'user', 'username', 'password');
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential($password);

        // Attempt authentication, saving the result
        $result = $auth->authenticate($authAdapter);
        $storage = $auth->getStorage();
        $storage->write($authAdapter->getResultRowObject(null, 'password'));
        return $result;
    }
    public function logoutAction()
    {
        App::clearUser();
        App::getUser(); // For goto login page
    }
}
