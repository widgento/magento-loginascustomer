<?php

class Widgento_Login_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * @return bool
     */
    public function isSaveLogs()
    {
        return Mage::getStoreConfig('widgentologin/general/save_logs');
    }
}