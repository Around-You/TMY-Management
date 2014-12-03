<?php
require_once 'BaseTestCase.php';

class WalkinThroughTest extends BaseTestCase
{
    public function testAllPageSimplyOrganization()
    {
        $this->url('/home/');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/calendar');
        $this->assertContains('Test Org', $this->source());

        $this->url('/events/list');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/past/');
        $this->assertNotContains('Test Payment', $this->source());

        $this->url('/crm/contacts');
        $this->assertContains('vincent.fong@symbio-group.com', $this->source());

        $this->url('/crm/leads');
        $this->assertContains('Test Org', $this->source());

        $this->url('/campaigns/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/campaigns/create/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/admin/profile/profile/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/admin/members');
        $this->assertContains('Test Org', $this->source());

        $this->url('/admin/edition');
        $this->assertContains('Test Org', $this->source());

        $this->url('/account/tasks/');
        $this->assertContains('Test Org', $this->source());

    }
    public function testAllPageSimplyViewEvent()
    {
        $this->url('/event/334');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/event/334/sponsors');
        $this->assertContains('Test Payment', $this->source());

    }
    public function testAllPageSimplyManageEvent()
    {

        $this->url('/create/event/');
        $this->assertContains('Test Org', $this->source());

        $this->url('/events/334/dashboard');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/members');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/settings');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/settings/contact_information/');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/settings/basic_information/');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/settings/registration_settings/');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/planner');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/details/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/details/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/timeAndVenue/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/timeAndVenue/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/tickets/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/tickets/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/agenda/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/agenda/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/sponsors/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/sponsors/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/speakers/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/speakers/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/banner/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/banner/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/documents/en');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/publisher/documents/zh');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/attendees/registrations/');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/checkin');
        $this->assertContains('Test Payment', $this->source());

        $this->url('/events/334/documents');
        $this->assertContains('Test Payment', $this->source());

    }

    public function testAllPageSimplyAccount()
    {

        $this->url('/register/account');
        $this->assertContains('EventBank Registration', $this->source());

        $this->url('/account/recover');
        $this->assertContains('Forgot your password', $this->source());

        $this->url('/account/notifications');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/notifications/account');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/notifications/organization');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/profile');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/settings');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/settings/password');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/settings/emails');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/settings/notifications');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/account/settings/details');
        $this->assertContains('Sam Xiao', $this->source());

        $this->url('/organization-account/settings');
        $this->assertContains('Test Org', $this->source());

        $this->url('/organization-account/settings/password');
        $this->assertContains('Test Org', $this->source());

        $this->url('/organization-account/settings/emails');
        $this->assertContains('Test Org', $this->source());

        $this->url('/organization-account/settings/notifications');
        $this->assertContains('Test Org', $this->source());

        $this->url('/organization-account/settings/details');
        $this->assertContains('Test Org', $this->source());

    }

}
