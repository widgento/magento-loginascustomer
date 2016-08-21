<?php

class Widgento_Login_Adminhtml_Widgentologin_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $customerId = $this->getRequest()->getParam('id');
        $customer   = Mage::getModel('customer/customer')->load($customerId);

        if (!Mage::helper('widgentologin')->isLoginAllowed($customerId) || !$customer->getId())
        {
            return $this->_redirect('admin/');
        }

        $hash = md5(uniqid(mt_rand(), true));
        Mage::getModel('widgentologin/login')
            ->setLoginHash($hash)
            ->setCustomerId($customerId)
            ->setAdminId(Mage::getSingleton('admin/session')->getUser()->getId())
            ->setCreatedAt(now())
            ->setIsActive(1)
            ->save();

        return $this->_redirect('widgentologin/', array(
        	'id'     => $hash, 
        	'_store' => Mage::helper('widgentologin')->getCustomerStoreId($customer->getId()), 
            ));
    }

    public function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/widgentologin');
    }
}
