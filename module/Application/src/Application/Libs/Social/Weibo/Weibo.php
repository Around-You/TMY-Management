<?php
namespace Application\Libs\Social;

class Weibo
{

    protected $clientId = '3208831613';

    protected $clientSecret = '46b6ba88abec3ca1cabb56afdb0a3204';

    protected $redirectUrl = 'jd.rx-tech.cn/oauth/login/weibo';

    protected $loginUrl = 'https://api.weibo.com/oauth2/authorize';

    protected $tokenUrl = 'https://api.weibo.com/oauth2/access_token';

    protected $code;

    /**
     *
     * @return the $loginUrl
     */
    public function getLoginUrl()
    {
        return $this->loginUrl . '?client_id=' . $this->clientId . '&response_type=code&redirect_uri=' . $this->redirectUrl;
    }

    /**
     *
     * @return the $tokenUrl
     */
    public function getTokenUrl()
    {
        return $this->tokenUrl;
    }

    /**
     *
     * @return the $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param field_type $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getToken($code)
    {
        $this->setCode($code);
        $url = $this->getTokenUrl();
        $data = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUrl,
            'code' => $this->getCode()
        );
        return $this->http($url, http_build_query($data), 'POST');
    }

    public function getUserInfo($token, $uid)
    {
        $url = 'https://api.weibo.com/2/users/show.json?';
        $data = array(
            'access_token' => $token,
            'uid' => $uid
        );
        $url .= http_build_query($data);
        return $this->http($url);
    }
}

