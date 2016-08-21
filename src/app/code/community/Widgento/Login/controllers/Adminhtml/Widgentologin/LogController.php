<?php

class Widgento_Login_Adminhtml_Widgentologin_LogController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('customer/widgentologin')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Customers'), Mage::helper('adminhtml')->__('Customers'))
        ;

        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout()
            ->renderLayout();
    }

    public function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/widgentologin/log');
    }

    public function gridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function clearAction()
    {
        Mage::getModel('widgentologin/login')->truncate();

        Mage::getSingleton('adminhtml/session')->addSuccess('Log has been cleared successfully.');

        $this->_redirect('*/*/index');
    }
}
