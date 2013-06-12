<?php
namespace VanOns\DirectAdminApi;

use Exception;
use VanOns\DirectAdminApi\Models\Package;
use VanOns\DirectAdminApi\Models\User;

class DirectAdmin
{

    private $username;
    private $password;
    private $port;
    private $host;

    /**
     * @var HttpSocket
     */
    private $socket;

    public function __construct($username, $password, $host = '127.0.0.1', $port = 2222)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
    }

    public function getIps() {
        $params = array();

        $function = 'CMD_API_SHOW_RESELLER_IPS';
        $result = $this->request(
            $function,
            $params
        );

        return $result['list'];
    }

    public function getAllUsers()
    {
        $function = 'CMD_API_SHOW_ALL_USERS';

        $result = $this->request($function);

        if (!array_key_exists('list', $result)) {
            throw new Exception("Couldn't retrieve users from DirectAdmin");
        }

        $return = array();

        foreach($result['list'] as $username) {
            $user = new User();
            $user->setUsername($username);

            $limits = $this->getUserLimits($username);
            $usage = $this->getUserUsage($username);

            $user->setBandwidthUsed($usage['bandwidth']);
            $user->setDiskSpaceUsed($usage['quota']);
            $user->setDomainCount($usage['vdomains']);
            $user->setEmailCount($usage['nemails']);

            $user->setBandwidthLimit($limits['bandwidth'] == 'unlimited' ? -1 : $limits['bandwidth']);
            $user->setDiskSpaceLimit($limits['quota'] == 'unlimited' ? -1 : $limits['quota']);
            $user->setDomainLimit($limits['vdomains'] == 'unlimited' ? -1 : $limits['vdomains']);
            $user->setEmailLimit($limits['nemails'] == 'unlimited' ? -1 : $limits['nemails']);

            $return[] = $user;
        }

        return $return;
    }

    public function getUserUsage($username) {
        $params = array(
            'user' => $username
        );

        $function = 'CMD_API_SHOW_USER_USAGE';
        $result = $this->request(
            $function,
            $params
        );

        return $result;
    }

    public function getUserLimits($username) {
        $params = array(
            'user' => $username
        );

        $function = 'CMD_API_SHOW_USER_CONFIG';
        $result = $this->request(
            $function,
            $params
        );

        return $result;
    }

    public function getEmailAccountQuota($domain, $username) {
        $params = array(
            'type' => 'quota',
            'domain' => $domain,
            'user' => $username
        );

        $function = 'CMD_API_POP';
        $result = $this->request(
            $function,
            $params
        );

        return $result;
    }

    public function getUserPackages() {
        $params = array();

        $function = 'CMD_API_PACKAGES_USER';
        $result = $this->request(
            $function,
            $params
        );

        $return = array();

        foreach($result['list'] as $packageName) {
            $package = new Package();
            $package->setName($packageName);
            $return[] = $package;
        }

        return $return;
    }

    public function createUser(User $user) {

        $params = array(
            'action' => 'create',
            'add' => 'Submit',
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'passwd' => $user->getPassword(),
            'passwd2' => $user->getPassword(),
            'domain' => $user->getDomain(),
            'package' => $user->getPackage()->getName(),
            'ip' => $user->getIp(),
            'notify' => 'no'
        );

        $function = 'CMD_API_ACCOUNT_USER';
        $result = $this->request(
            $function,
            $params
        );

        return $result;

    }

    public function deleteUsers($users = array()) {

        $params = array('confirmed' => 'Confirm', 'delete' => 'yes');

        foreach($users as $i => $user) {
            $params['select' . $i] = $user;
        }

        $function = 'CMD_API_SELECT_USERS';
        $result = $this->request(
            $function,
            $params,
            'POST'
        );

        return $result;
    }

    public function getDomains()
    {
        $function = 'CMD_API_SHOW_DOMAINS';
        $result = $this->request($function);

        if (!array_key_exists('list', $result)) {
            throw new Exception("Couldn't retrieve domains from DirectAdmin");
        }

        return $result['list'];
    }

    public function createUserPackage(
        $name,
        $bandwidth = 1000,
        $diskSpace = 100,
        $domainLimit = 1,
        $subDomainLimit = 0,
        $emailAccountLimit = 1,
        $emailForwarderLimit = 0,
        $mailingListLimit = 0,
        $autoResponderLimit = 0,
        $databaseLimit = 0,
        $domainPointerLimit = 0,
        $ftpAccountLimit = 1,
        $anonymousFtpAccess = false,
        $cgiAccess = true,
        $phpAccess = true,
        $spamAssassin = true,
        $catchAllEmail = true,
        $sslAccess = true,
        $sshAccess = true,
        $cronJobs = true,
        $systemInfo = true,
        $dnsControl = false,
        $suspendAtLimit = true,
        $skin = 'enhanced',
        $language = 'en'
    ) {

        $function = 'CMD_API_MANAGE_USER_PACKAGES';
        $result = $this->request(
            $function,
            array(
                'add' => 'Save',
                'packagename' => $name,
                'bandwidth' => $bandwidth,
                'quota' => $diskSpace,
                'vdomains' => $domainLimit,
                'nsubdomains' => $subDomainLimit,
                'nemails' => $emailAccountLimit,
                'nemailf' => $emailForwarderLimit,
                'nemailml' => $mailingListLimit,
                'nemailr' => $autoResponderLimit,
                'mysql' => $databaseLimit,
                'domainptr' => $domainPointerLimit,
                'ftp' => $ftpAccountLimit,
                'aftp' => $anonymousFtpAccess ? 'ON' : 'OFF',
                'cgi' => $cgiAccess ? 'ON' : 'OFF',
                'php' => $phpAccess ? 'ON' : 'OFF',
                'spam' => $spamAssassin ? 'ON' : 'OFF',
                'catchall' => $catchAllEmail ? 'ON' : 'OFF',
                'ssl' => $sslAccess ? 'ON' : 'OFF',
                'ssh' => $sshAccess ? 'ON' : 'OFF',
                'cron' => $cronJobs ? 'ON' : 'OFF',
                'sysinfo' => $systemInfo ? 'ON' : 'OFF',
                'dnscontrol' => $dnsControl ? 'ON' : 'OFF',
                'suspend_at_limit' => $suspendAtLimit ? 'ON' : 'OFF',
                'skin' => $skin,
                'language' => $language
            )
        );

        return $result;
    }

    public function deleteUserPackage($packages = array()) {

        $params = array('delete' => 'Delete');

        foreach($packages as $i => $package) {
            $params['delete' . $i] = $package;
        }

        $function = 'CMD_API_MANAGE_USER_PACKAGES';
        $result = $this->request(
            $function,
            $params
        );

        return $result;
    }

    public function getPopEmailAccounts($domain)
    {
        $function = 'CMD_API_POP';
        $result = $this->request(
            $function,
            array(
                'action' => 'list',
                'domain' => $domain
            )
        );

        if (!array_key_exists('list', $result)) {
            return array();
        }

        return $result['list'];
    }

    public function createPopEmailAccount($domain, $username, $password, $quota = 0, $limit = 0)
    {
        $function = 'CMD_API_POP';
        $result = $this->request(
            $function,
            array(
                'action' => 'create',
                'domain' => $domain,
                'user' => $username,
                'passwd' => $password,
                'passwd2' => $password,
                'quota' => $quota,
                'limit' => $limit
            )
        );

        return $result;
    }

    public function deletePopEmailAccount($domain, $username)
    {
        $function = 'CMD_API_POP';
        $result = $this->request(
            $function,
            array(
                'action' => 'delete',
                'domain' => $domain,
                'user' => $username
            )
        );

        return $result;
    }

    private function request($function, $parameters = array(), $method = 'GET')
    {

        $socket = $this->getSocket();
        $socket->set_method($method);
        $socket->query('/' . $function, $parameters);

        $result = $socket->fetch_parsed_body();

        return $result;
    }

    private function getSocket()
    {
        if ($this->socket == null) {
            $this->socket = new HttpSocket();
            $this->socket->connect($this->host, $this->port);
            $this->socket->set_login($this->username, $this->password);
        }

        return $this->socket;
    }

    public function loginAs($username)
    {
        $socket = $this->getSocket();
        $socket->set_login('admin|' . $username, $this->password);
    }

    public function resetLogin()
    {
        $socket = $this->getSocket();
        $socket->set_login($this->username, $this->password);
    }

    public function __destruct()
    {
        if ($this->socket != null) {

            /**
             * @todo Close socket
             */

        }
    }

}