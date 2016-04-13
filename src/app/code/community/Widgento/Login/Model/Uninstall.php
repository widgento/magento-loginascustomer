<?php

class Widgento_Login_Model_Uninstall extends Widgento_Core_Model_Uninstall_Abstract
{
    public function run()
    {
        $this->_setup->run('DROP TABLE IF EXISTS `'.$this->_setup->getTable('widgentologin/login').';');
    }
}