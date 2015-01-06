<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModelMapper;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthDbTableAdapter;
use Zend\Authentication\Result;

class UserAuthenticator extends AbstractModelMapper
{

    protected $staffTable;

    public function getStaffTable()
    {
        if (! $this->staffTable) {
            $this->staffTable = $this->getServiceLocator()->get('Application\Model\Account\StaffTable');
        }

        return $this->staffTable;
    }

    /**
     * Email | Username
     * Password
     *
     * @param string $identity
     * @param string $credential
     * @return Result
     * @throws Exception\RuntimeException
     */
    public function doPasswordAuth($identity, $credential)
    {
        try {
            $table = $this->getStaffTable();
            $staff = $table->getOneByLoginName($identity);
            $credential = $staff->encryptPassword($credential,$identity);
            if ($credential == $staff->password) {
                $this->setIdentity($staff);
                return Result::SUCCESS;
            }else{
                return Result::FAILURE_CREDENTIAL_INVALID;
            }
        }catch (\Exception $e){
            return Result::FAILURE_IDENTITY_NOT_FOUND;
        }
//         $auth = new AuthenticationService();
//         $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//         $authAdapter = new AuthDbTableAdapter($dbAdapter, 'user', 'username', 'password');
//         $authAdapter->setIdentity($identity);
//         $authAdapter->setCredential($credential);

//         // Attempt authentication, saving the result
//         $result = $auth->authenticate($authAdapter);
//         $storage = $auth->getStorage();
//         $storage->write($authAdapter->getResultRowObject(null, 'password'));
//         return $result;
    }





    protected function setIdentity(Staff $staff)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getStorage();
        $storage->write($staff);
        return true;
    }
}

