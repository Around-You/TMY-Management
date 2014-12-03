<?php
namespace Application\Model\Account;

use SamFramework\Model\AbstractModelMapper;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthDbTableAdapter;
use SamFramework\Core\App;
use Application\Model\Account\Social\Weibo;

class UserAuthenticator extends AbstractModelMapper
{

    protected $userTable;

    public function getUserTable()
    {
        if (! $this->userTable) {
            $this->userTable = $this->getServiceLocator()->get('Application\Model\Account\UserTable');
        }

        return $this->userTable;
    }

    /**
     * Email | Username
     * Password
     *
     * @param string $identity
     * @param string $credential
     * @return boolean
     */
    public function doPasswordAuth($identity, $credential)
    {
        return $this->setIdentity("");
    }

    public function existWeiboAccount($token)
    {
        $table = $this->getUserTable();
        try {
            $user = $table->getUserByWeiBoToken($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *
     * @param unknown $token
     * @return boolean
     */
    public function doWeiboAuth($tokenArr)
    {
        $table = $this->getUserTable();
        try {
            $user = $table->getUserByWeiBoToken($tokenArr['access_token']);
            $this->saveTokenToUser($tokenArr, $user);
            return $this->setIdentity($user);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function saveTokenToUser($tokenArr, User $user)
    {
        $socialModel = new Weibo();
        $table = $this->getUserTable();
        $expiryAt = time() + $tokenArr['expires_in'];
        $user->weibo_token_expiry = date('Y-m-d H:i:s', $expiryAt);
        $user->weibo_token = $tokenArr['access_token'];
        $user->weibo_id = $tokenArr['uid'];
        $userInfo = $socialModel->getUserInfo($user->weibo_token, $user->weibo_id);
        $user->weibo_name = $userInfo['name'];
        return $table->saveUser($user);
    }

    protected function setIdentity(User $user)
    {
        $auth = new AuthenticationService();
        $storage = $auth->getStorage();
        $storage->write($user);
        return true;
    }
}

