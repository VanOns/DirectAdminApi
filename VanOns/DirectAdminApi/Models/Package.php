<?php
/**
 * Created by JetBrains PhpStorm.
 * User: machiel
 * Date: 6/12/13
 * Time: 7:18 PM
 * To change this template use File | Settings | File Templates.
 */

namespace VanOns\DirectAdminApi\Models;


class Package {

    private $name;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

}