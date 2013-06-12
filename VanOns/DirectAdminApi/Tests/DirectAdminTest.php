<?php
/**
 * Created by JetBrains PhpStorm.
 * User: machiel
 * Date: 6/12/13
 * Time: 3:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace VanOns\DirectAdminApi\Tests;


use VanOns\DirectAdminApi\DirectAdmin;

class DirectAdminTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DirectAdmin
     */
    private $directAdmin;

    public function testUserPackageCreation() {

        $result = $this->directAdmin->createUserPackage(
            'test_package',
            5000, // bandwidth
            50, // space
            5, // domains
            15, // subdomains
            20, // email accounts
            100, // email forwarders
            3, // mailing lists,
            18, // auto responders
            10, // mysql databases
            1000, // domain pointers
            154, // ftp accounts
            true, // anon ftp
            false, // cgi access
            true, // php access
            true, // spam assassin
            false, // catch all email
            true, // ssl
            false, // ssh
            true, // crons
            false, // sys info
            true, // dns management
            false, // suspend @ limit
            'power_user', // skin,
            'en' // language
        );

        $this->assertEquals(0, $result['error']);

    }


    /**
     * @depends testUserPackageCreation
     */
    public function testUserCreation() {

        $result = $this->directAdmin->createUser('testuser', 'test@test.usr', 'TEST_PASS', 'example.com', 'test_package', '37.230.97.245', 'no');
        $this->assertEquals(0, $result['error']);

    }

    /**
     * @depends testUserCreation
     */
    public function testUserLimits() {

        $limits = $this->directAdmin->getUserLimits('testuser');
        $this->assertEquals(50, $limits['quota']);

    }

    /**
     * @depends testUserPackageCreation
     */
    public function testGetUserPackages() {

        $result = $this->directAdmin->getUserPackages();

        $this->assertContains('test_package', $result);

    }

    /**
     * @depends testUserCreation
     */
    public function testEmailCreation()
    {

        $this->directAdmin->loginAs('testuser');
        $result = $this->directAdmin->createPopEmailAccount('example.com', 'info', 'HALLO$$$HALLO', 50);

        $this->assertEquals(0, $result['error']);

        $accounts = $this->directAdmin->getPopEmailAccounts('example.com');

        $this->assertContains('info', $accounts);

        $this->directAdmin->resetLogin();

    }

    /**
     * @depends testEmailCreation
     */
    public function testUserEmailQuota() {

        $this->directAdmin->loginAs('testuser');
        $result = $this->directAdmin->getEmailAccountQuota('example.com', 'info');

        $this->assertArrayNotHasKey('error', $result);
        $this->directAdmin->resetLogin();

    }

    /**
     * @depends testUserEmailQuota
     */
    public function testEmailDeletion() {

        $this->directAdmin->loginAs('testuser');

        $this->directAdmin->deletePopEmailAccount('example.com', 'info');
        $accounts = $this->directAdmin->getPopEmailAccounts('example.com');

        $this->assertNotContains('info', $accounts);

        $this->directAdmin->resetLogin();

    }

    /**
     * @depends testUserCreation
     */
    public function testUserDeletion() {
        $result = $this->directAdmin->deleteUsers(array('testuser'));
        $this->assertEquals(0, $result['error']);
    }

    /**
     * @depends testGetUserPackages
     */
    public function testUserPackageDeletion() {
        $result = $this->directAdmin->deleteUserPackage(array('test_package'));
        $this->assertEquals(0, $result['error']);
    }

    protected function setUp()
    {

        $this->directAdmin = new DirectAdmin('username', 'password');

    }

    protected function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}
