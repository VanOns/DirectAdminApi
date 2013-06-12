<?php
/**
 * Created by JetBrains PhpStorm.
 * User: machiel
 * Date: 6/12/13
 * Time: 6:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace VanOns\DirectAdminApi\Models;

class User {

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var Package
     */
    private $package;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var int
     */
    private $bandwidthUsed;

    /**
     * @var int
     */
    private $diskSpaceUsed;

    /**
     * @var int
     */
    private $domainCount;

    /**
     * @var int
     */
    private $emailCount;

    /**
     * @var int
     */
    private $bandwidthLimit;

    /**
     * @var int
     */
    private $diskSpaceLimit;

    /**
     * @var int
     */
    private $domainLimit;

    /**
     * @var int
     */
    private $emailLimit;

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param \VanOns\DirectAdminApi\Models\Package $package
     */
    public function setPackage($package)
    {
        $this->package = $package;
    }

    /**
     * @return \VanOns\DirectAdminApi\Models\Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param int $bandwidthLimit
     */
    public function setBandwidthLimit($bandwidthLimit)
    {
        $this->bandwidthLimit = $bandwidthLimit;
    }

    /**
     * @return int
     */
    public function getBandwidthLimit()
    {
        return $this->bandwidthLimit;
    }

    /**
     * @param int $bandwidthUsed
     */
    public function setBandwidthUsed($bandwidthUsed)
    {
        $this->bandwidthUsed = $bandwidthUsed;
    }

    /**
     * @return int
     */
    public function getBandwidthUsed()
    {
        return $this->bandwidthUsed;
    }

    /**
     * @param int $diskSpaceLimit
     */
    public function setDiskSpaceLimit($diskSpaceLimit)
    {
        $this->diskSpaceLimit = $diskSpaceLimit;
    }

    /**
     * @return int
     */
    public function getDiskSpaceLimit()
    {
        return $this->diskSpaceLimit;
    }

    /**
     * @param int $diskSpaceUsed
     */
    public function setDiskSpaceUsed($diskSpaceUsed)
    {
        $this->diskSpaceUsed = $diskSpaceUsed;
    }

    /**
     * @return int
     */
    public function getDiskSpaceUsed()
    {
        return $this->diskSpaceUsed;
    }

    /**
     * @param int $domainCount
     */
    public function setDomainCount($domainCount)
    {
        $this->domainCount = $domainCount;
    }

    /**
     * @return int
     */
    public function getDomainCount()
    {
        return $this->domainCount;
    }

    /**
     * @param int $domainLimit
     */
    public function setDomainLimit($domainLimit)
    {
        $this->domainLimit = $domainLimit;
    }

    /**
     * @return int
     */
    public function getDomainLimit()
    {
        return $this->domainLimit;
    }

    /**
     * @param int $emailCount
     */
    public function setEmailCount($emailCount)
    {
        $this->emailCount = $emailCount;
    }

    /**
     * @return int
     */
    public function getEmailCount()
    {
        return $this->emailCount;
    }

    /**
     * @param int $emailLimit
     */
    public function setEmailLimit($emailLimit)
    {
        $this->emailLimit = $emailLimit;
    }

    /**
     * @return int
     */
    public function getEmailLimit()
    {
        return $this->emailLimit;
    }

}