<?php

/**
 * Class Widgento_Login_Model_Login
 *
 * @method Widgento_Login_Model_Login setStoreId(int $value)
 * @method Widgento_Login_Model_Login setLoginHash(string $value)
 * @method Widgento_Login_Model_Login setIsActive(bool $value)
 */
class Widgento_Login_Model_Login extends Mage_Catalog_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('widgentologin/login');
    }

    public function truncate()
    {
        $this->getResource()->truncate();

        return $this;
    }
}