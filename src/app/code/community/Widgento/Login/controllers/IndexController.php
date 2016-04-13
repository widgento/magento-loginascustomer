<?php
 
class Widgento_Login_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $hash     = $this->getRequest()->getParam('id');
        $login    = Mage::getModel('widgentologin/login')->load($hash, 'login_hash');
        $isActive = $login->getIsActive();

        if (!Mage::helper('widgentologin')->isSaveLogs())
        {
            $login->truncate();
        }
        else
        {
            $login
                ->setStoreId(Mage::app()->getStore()->getId())
                ->setLoginHash('')
                ->setIsActive(0)
                ->save();
        }

        if ($isActive && $login->getCustomerId())
        {
            /* @var $customerSession Mage_Customer_Model_Session */
            $customerSession = Mage::getSingleton('customer/session');

            Mage::dispatchEvent('widgentologin_before_login');

            Mage::getSingleton('checkout/session')->clear();
            Mage::getSingleton('catalog/session')->clear();
            Mage::getSingleton('core/session')->clear();
            Mage::getSingleton('customer/session')->clear();
            Mage::getSingleton('newsletter/session')->clear();
            Mage::getSingleton('paypal/session')->clear();
            Mage::getSingleton('paypal/session')->clear();
            Mage::getSingleton('reports/session')->clear();
            Mage::getSingleton('review/session')->clear();
            Mage::getSingleton('wishlist/session')->clear();
            Mage::getSingleton('catalogsearch/session')->clear();
            Mage::getSingleton('paypaluk/session')->clear();

            if ($customerSession->getCustomerId())
            {
                $persistentSession = Mage::getSingleton('persistent/session');

                if ($persistentSession)
                {
                    $persistentSession
                        ->clear()
                        ->deleteByCustomerId($customerSession->getCustomerId());
                }
            }

            if (method_exists($customerSession, 'renewSession'))
            {
                $customerSession->renewSession();
            }
            else // for 1.4
            {
                $customerSession->logout();
            }

            $customerSession->loginById($login->getCustomerId());

            return $this->_redirect('customer/account/');
        }

        return $this->_redirect('');
    }
}
