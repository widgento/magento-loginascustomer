<?php

class Widgento_Login_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup     = 'widgentologin';
        $this->_controller     = 'adminhtml_log';
        $this->_headerText     = Mage::helper('widgentologin')->__('Login as Customer Logs');

        parent::__construct();

        $this->_removeButton('add');
        $this->_addButton('flush', array(
            'label'     => Mage::helper('widgentologin')->__('Clear Logs'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('adminhtml/widgentologin_log/clear') .'\')',
            'class'     => 'delete',
        ));

    }
}
