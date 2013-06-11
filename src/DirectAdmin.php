<?php
namespace VanOns\DirectAdminApi;

use Exception;

class DirectAdmin {

    private $username;
    private $password;
    private $port;
    private $host;

    /**
     * @var HttpSocket
     */
    private $socket;

    public function __construct($username, $password, $host = '127.0.0.1', $port = 2222) {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
    }

    public function getAllUsers() {
        $function = 'CMD_API_SHOW_ALL_USERS';

        $result = $this->request($function);

        if(!array_key_exists('list', $result)) {
            throw new Exception("Couldn't retrieve users from DirectAdmin");
        }

        return $result['list'];
    }

    public function getDomains() {
        $function = 'CMD_API_SHOW_DOMAINS';
        $result = $this->request($function);

        if(!array_key_exists('list', $result)) {
            throw new Exception("Couldn't retrieve domains from DirectAdmin");
        }

        return $result['list'];
    }

    public function getPopEmailAccounts($domain) {
        $function = 'CMD_API_POP';
        $result = $this->request($function, array(
            'action' => 'list',
            'domain' => $domain
        ));

//        if(!array_key_exists('list', $result)) {
//            throw new Exception("Couldn't retrieve email accounts from DirectAdmin");
//        }

        return $result;
    }

    public function createPopEmailAccount($domain, $username, $password, $quota = 0, $limit = 0) {
        $function = 'CMD_API_POP';
        $result = $this->request($function, array(
            'action' => 'create',
            'domain' => $domain,
            'user' => $username,
            'passwd' => $password,
            'passwd2' => $password,
            'quota' => $quota,
            'limit' => $limit
        ));

        return $result;
    }

    private function request($function, $parameters = array()) {

        $socket = $this->getSocket();
        $socket->query('/' . $function, $parameters);

        $result = $socket->fetch_parsed_body();
        return $result;
    }

    private function getSocket() {
        if($this->socket == null) {
            $this->socket = new HttpSocket();
            $this->socket->connect($this->host, $this->port);
            $this->socket->set_login($this->username, $this->password);
        }

        return $this->socket;
    }

    public function loginAs($username) {
        $socket = $this->getSocket();
        $socket->set_login('admin|' . $username, $this->password);
    }

    public function resetLogin() {
        $socket = $this->getSocket();
        $socket->set_login($this->username, $this->password);
    }

    public function __destruct() {
        if($this->socket != null) {

            /**
             * @todo Close socket
             */

        }
    }

}