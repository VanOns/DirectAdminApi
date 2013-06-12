<?php
namespace VanOns\DirectAdminApi;

use Exception;

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

    public function getAllUsers()
    {
        $function = 'CMD_API_SHOW_ALL_USERS';

        $result = $this->request($function);

        if (!array_key_exists('list', $result)) {
            throw new Exception("Couldn't retrieve users from DirectAdmin");
        }

        return $result['list'];
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

    public function createUser($username, $email, $password, $domain, $package, $ip, $notify = 'yes') {

        $params = array(
            'action' => 'create',
            'add' => 'Submit',
            'username' => $username,
            'email' => $email,
            'passwd' => $password,
            'passwd2' => $password,
            'domain' => $domain,
            'package' => $package,
            'ip' => $ip,
            'notify' => $notify
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

    public function getUserPackages() {
        $function = 'CMD_API_PACKAGES_USER';
        $result = $this->request(
            $function
        );

        return $result['list'];
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