<?php

class BaseTestCase extends PHPUnit_Extensions_Selenium2TestCase
{

    protected $config;

    public function getConfig()
    {
        if (! $this->config) {
            $localConfig = array();
            if (file_exists(__DIR__ . '/config/config.local.php')) {
                $localConfig = require __DIR__ . '\config\config.local.php';
            }
            $config = require __DIR__ . '/config/config.php';
            $this->config = array_merge($config, $localConfig);
        }
        return $this->config;
    }

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $config = $this->getConfig();
        $this->setBrowser($config['Browser']);
        if (isset($config['DesiredCapabilities'])) {
            $this->setDesiredCapabilities($config['DesiredCapabilities']);
        }
        $this->setSeleniumServerRequestsTimeout(50000);
        $this->setBrowserUrl($config['BrowserUrl']);
    }

    public function setUpPage()
    {
    }

    public function login()
    {
        $config = $this->getConfig();
        $this->url('/account/login');
        $this->assertStringEndsWith('login', $this->url());
        $username = $this->byName('username');
        $username->value($config['username']);
        $username = $this->byName('password');
        $username->value($config['password']);
        $submit = $this->byCssSelector('button[type=submit]');
        $submit->click();
    }
}
