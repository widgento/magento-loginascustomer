<?php

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