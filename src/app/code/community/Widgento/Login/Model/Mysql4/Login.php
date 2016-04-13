<?php

class Widgento_Login_Model_Mysql4_Login extends Mage_Core_Model_Mysql4_Abstract
{
    /**
    * Initialize resource
    */
    protected function _construct()
    {
        $this->_init('widgentologin/login', 'login_id');
    }

    public function truncate()
    {
        $db = $this->_getWriteAdapter();
        if (method_exists($db, 'truncateTable'))
        {
            $db->truncateTable($this->getMainTable());
        }
        else
        {
            $db->truncate($this->getMainTable());
        }
    }
}