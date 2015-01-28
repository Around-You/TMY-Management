<?php
require_once 'BaseTestCase.php';

class WalkinThroughTest extends BaseTestCase
{

    public function testAllPageSimplyOrganization()
    {
        $this->url('/dashboard');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/member');
        $this->assertContains('Test Org', $this->source());

        $this->url('/member/add');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/sale/use/');
        $this->assertNotContains('Test Payment', $this->source());

        $this->url('/goods');
        $this->assertContains('vincent.fong@symbio-group.com', $this->source());

        $this->url('/category');
        $this->assertContains('Test Org', $this->source());

        $this->url('/sale/quick/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/store/sellLog/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/store/memberLog/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/staff');
        $this->assertContains('Test Org', $this->source());
    }
}
